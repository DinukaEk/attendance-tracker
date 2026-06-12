<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $subjects = Subject::all();
        $dateFrom = $request->get('date_from', now()->subWeek()->toDateString());
        $dateTo   = $request->get('date_to',   now()->toDateString());
        $subjectId = $request->get('subject_id');

        // Optimised aggregate query — uses DB indexes, not PHP loops
        $results = Student::select('students.id', 'students.name', 'students.registration_number')
            ->selectRaw('COUNT(a.id) as total_classes')
            ->selectRaw('SUM(CASE WHEN a.present = 1 THEN 1 ELSE 0 END) as attended')
            ->selectRaw('ROUND((SUM(CASE WHEN a.present = 1 THEN 1 ELSE 0 END) / NULLIF(COUNT(a.id),0)) * 100, 2) as percentage')
            ->leftJoin('attendances as a', function($join) use ($dateFrom, $dateTo, $subjectId) {
                $join->on('a.student_id', '=', 'students.id')
                    ->whereBetween('a.date', [$dateFrom, $dateTo]);
                if ($subjectId) $join->where('a.subject_id', $subjectId);
            })
            ->when($subjectId, fn($q) => $q->whereHas('subjects', fn($s) => $s->where('subject_id', $subjectId)))
            ->groupBy('students.id', 'students.name', 'students.registration_number')
            ->orderByDesc('percentage')
            ->paginate(50);

        return view('dashboard.index', compact('results', 'subjects', 'dateFrom', 'dateTo', 'subjectId'));
    }
}
