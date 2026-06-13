<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Subject;

class StudentController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        $students = collect();

        if ($query) {
            $students = Student::where('name', 'like', "%{$query}%")
                ->orWhere('registration_number', 'like', "%{$query}%")
                ->limit(20)
                ->get();

            $user = auth()->user();
            if (!$user->isAdmin()) {
                $teacherSubjectIds = $user->subjects->pluck('id')->toArray();
                $students = $students->filter(function ($student) use ($teacherSubjectIds) {
                    return $student->subjects->pluck('id')->intersect($teacherSubjectIds)->isNotEmpty();
                });
            }
        }

        return view('students.search', compact('students', 'query'));
    }

    public function show(Student $student, Request $request)
    {
        $user = auth()->user();

        $student->load('subjects');
        $subjects = $student->subjects;

        if (!$user->isAdmin()) {
            $teacherSubjectIds = $user->subjects->pluck('id')->toArray();
            $subjects = $subjects->filter(fn($s) => in_array($s->id, $teacherSubjectIds));

            if ($subjects->isEmpty()) {
                abort(403, 'You do not have access to this student\'s records.');
            }
        }

        $dateFrom = $request->get('date_from', now()->subWeek()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        // Per-subject summary
        $summary = [];
        foreach ($subjects as $subject) {
            $total = Attendance::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->count();

            $present = Attendance::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->where('present', true)
                ->count();

            $summary[] = [
                'subject' => $subject,
                'total' => $total,
                'present' => $present,
                'percentage' => $total > 0 ? round(($present / $total) * 100, 2) : null,
            ];
        }

        // Day-by-day log
        $log = Attendance::where('student_id', $student->id)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->with('subject')
            ->orderByDesc('date')
            ->paginate(50)
            ->withQueryString();

        return view('students.show', compact('student', 'summary', 'log', 'dateFrom', 'dateTo'));
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $students = Student::where('name', 'like', "%{$query}%")
            ->orWhere('registration_number', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'registration_number']);

        $user = auth()->user();
        if (!$user->isAdmin()) {
            $teacherSubjectIds = $user->subjects->pluck('id')->toArray();
            $students = $students->filter(function ($student) use ($teacherSubjectIds) {
                return $student->subjects->pluck('id')->intersect($teacherSubjectIds)->isNotEmpty();
            })->values();
        }

        return response()->json($students);
    }
}