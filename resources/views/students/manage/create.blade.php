@extends('layouts.custom')

@section('title', 'Add Student')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-800">Add Student</h1>
        <p class="mt-1 text-sm text-stone-500">Create a new student and enrol them in subjects.</p>
    </div>
    <a href="{{ route('students.manage.index') }}"
       class="px-4 py-2 text-sm transition-colors bg-white border rounded-lg shadow-sm hover:bg-stone-50 text-stone-600 border-stone-200">
        ← Back
    </a>
</div>

<form action="{{ route('students.manage.store') }}" method="POST">
    @csrf
    @include('students.manage._form')
    <div class="flex gap-3 mt-6">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors">
            Create Student
        </button>
        <a href="{{ route('students.manage.index') }}" class="px-6 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-600 rounded-lg text-sm transition-colors">
            Cancel
        </a>
    </div>
</form>

@endsection