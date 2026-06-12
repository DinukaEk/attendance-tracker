<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index() {
        $subjects = Subject::all();
        return view('attendance.index', compact('subjects'));
    }

    public function getStudents(Request $request) {
        // AJAX: return students enrolled in a subject
        $students = Student::whereHas('subjects', function($q) use ($request) {
            $q->where('subject_id', $request->subject_id);
        })->get();
        return response()->json($students);
    }

    public function store(Request $request) {
        foreach ($request->attendance as $studentId => $present) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'subject_id' => $request->subject_id, 'date' => today()],
                ['present' => $present === 'on']
            );
        }
        return back()->with('success', 'Attendance saved!');
    }
}
