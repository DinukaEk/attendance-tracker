<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $user = auth()->user();

        $students = Student::with('subjects')
            ->when($query, fn($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('registration_number', 'like', "%{$query}%"))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('students.manage.index', compact('students', 'query'));
    }

    public function create()
    {
        $user = auth()->user();
        $subjects = $user->isAdmin() ? Subject::all() : Subject::where('user_id', $user->id)->get();
        return view('students.manage.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:50|unique:students,registration_number',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $student = Student::create([
            'name' => $validated['name'],
            'registration_number' => $validated['registration_number'],
        ]);

        if (!empty($validated['subjects'])) {
            $student->subjects()->attach($validated['subjects']);
        }

        return redirect()->route('students.manage.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $user = auth()->user();
        $subjects = $user->isAdmin() ? Subject::all() : Subject::where('user_id', $user->id)->get();
        $student->load('subjects');
        return view('students.manage.edit', compact('student', 'subjects'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:50|unique:students,registration_number,' . $student->id,
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $student->update([
            'name' => $validated['name'],
            'registration_number' => $validated['registration_number'],
        ]);

        $user = auth()->user();
        if ($user->isAdmin()) {
            // Admin can reassign all subjects freely
            $student->subjects()->sync($validated['subjects'] ?? []);
        } else {
            // Teacher can only change enrollment for their own subjects
            $teacherSubjectIds = $user->subjects->pluck('id')->toArray();
            $selectedTeacherSubjects = collect($validated['subjects'] ?? [])
                ->filter(fn($id) => in_array($id, $teacherSubjectIds))
                ->toArray();

            // Detach only teacher's subjects, then reattach selected ones
            $student->subjects()->detach($teacherSubjectIds);
            if (!empty($selectedTeacherSubjects)) {
                $student->subjects()->attach($selectedTeacherSubjects);
            }
        }

        return redirect()->route('students.manage.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->subjects()->detach();
        $student->delete();
        return redirect()->route('students.manage.index')->with('success', 'Student deleted successfully.');
    }
}