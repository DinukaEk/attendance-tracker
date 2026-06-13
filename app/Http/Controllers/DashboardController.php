<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $subjects = $user->isAdmin() ? Subject::all() : Subject::where('user_id', $user->id)->get();

        $dateFrom = $request->get('date_from', now()->subWeek()->toDateString());
        $dateTo   = $request->get('date_to', now()->toDateString());
        $subjectId = $request->get('subject_id');

        $results = $this->getAttendanceResults($dateFrom, $dateTo, $subjectId);

        return view('dashboard.index', compact('results', 'subjects', 'dateFrom', 'dateTo', 'subjectId'));
    }

    public function data(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subWeek()->toDateString());
        $dateTo   = $request->get('date_to', now()->toDateString());
        $subjectId = $request->get('subject_id');

        $results = $this->getAttendanceResults($dateFrom, $dateTo, $subjectId);

        return response()->json([
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'total' => $results->total(),
            ],
        ]);
    }

    private function getAttendanceResults($dateFrom, $dateTo, $subjectId)
    {
        $user = auth()->user();
        $page = request('page', 1);
        $cacheKey = "dashboard:{$dateFrom}:{$dateTo}:{$subjectId}:page{$page}:user{$user->id}";

        return Cache::remember($cacheKey, 60, function () use ($dateFrom, $dateTo, $subjectId, $user) {
            $teacherSubjectIds = $user->isAdmin() ? null : $user->subjects->pluck('id')->toArray();

            return Student::select('students.id', 'students.name', 'students.registration_number')
                ->selectRaw('COUNT(a.id) as total_classes')
                ->selectRaw('SUM(CASE WHEN a.present = 1 THEN 1 ELSE 0 END) as attended')
                ->selectRaw('ROUND((SUM(CASE WHEN a.present = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(a.id),0)) * 100, 2) as percentage')
                ->leftJoin('attendances as a', function ($join) use ($dateFrom, $dateTo, $subjectId, $teacherSubjectIds) {
                    $join->on('a.student_id', '=', 'students.id')
                        ->whereBetween('a.date', [$dateFrom, $dateTo]);
                    if ($subjectId) {
                        $join->where('a.subject_id', $subjectId);
                    } elseif ($teacherSubjectIds !== null) {
                        $join->whereIn('a.subject_id', $teacherSubjectIds);
                    }
                })
                ->when($subjectId, fn($q) => $q->whereHas('subjects', fn($s) => $s->where('subject_id', $subjectId)))
                ->when(!$subjectId && $teacherSubjectIds !== null, fn($q) => $q->whereHas('subjects', fn($s) => $s->whereIn('subject_id', $teacherSubjectIds)))
                ->groupBy('students.id', 'students.name', 'students.registration_number')
                ->orderByDesc('percentage')
                ->paginate(50);
        });
    }
}