<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->with('subjects')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'subjects' => 'array',
        ]);

        $teacher = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
        ]);

        if (!empty($validated['subjects'])) {
            Subject::whereIn('id', $validated['subjects'])->update(['user_id' => $teacher->id]);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully.');
    }

    public function edit(User $teacher)
    {
        $subjects = Subject::all();
        $teacher->load('subjects');
        return view('admin.teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, User $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->id,
            'password' => 'nullable|min:6',
            'subjects' => 'array',
        ]);

        $teacher->name = $validated['name'];
        $teacher->email = $validated['email'];
        if (!empty($validated['password'])) {
            $teacher->password = bcrypt($validated['password']);
        }
        $teacher->save();

        // Unassign all subjects from this teacher, then reassign selected ones
        Subject::where('user_id', $teacher->id)->update(['user_id' => null]);
        if (!empty($validated['subjects'])) {
            Subject::whereIn('id', $validated['subjects'])->update(['user_id' => $teacher->id]);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher)
    {
        Subject::where('user_id', $teacher->id)->update(['user_id' => null]);
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}