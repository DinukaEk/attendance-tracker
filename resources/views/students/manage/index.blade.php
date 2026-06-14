@extends('layouts.custom')

@section('title', 'Manage Students')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-800">Manage Students</h1>
        <p class="mt-1 text-sm text-stone-500">Add, edit, or remove students and manage their subject enrolments.</p>
    </div>
    <a href="{{ route('students.manage.create') }}"
       class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-500">
        + Add Student
    </a>
</div>

@if(session('success'))
    <div class="px-4 py-3 mb-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('students.manage.index') }}" class="flex gap-3 mb-4">
    <div class="relative flex-1 max-w-sm">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </div>
        <input type="text" name="q" value="{{ $query }}" placeholder="Search students..."
               class="w-full py-2 pr-4 text-sm bg-white border rounded-lg shadow-sm border-stone-200 text-stone-800 pl-9 focus:outline-none focus:ring-2 focus:ring-indigo-400 placeholder-stone-400">
    </div>
    <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-500">Search</button>
    @if($query)
        <a href="{{ route('students.manage.index') }}" class="px-4 py-2 text-sm transition-colors rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-600">Clear</a>
    @endif
</form>

<div class="overflow-hidden bg-white border shadow-sm border-stone-200 rounded-xl">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-xs tracking-wide uppercase border-b bg-stone-50 text-stone-400 border-stone-200">
                <th class="px-4 py-3 text-left">Reg. No.</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Enrolled Subjects</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($students as $student)
                <tr class="transition-colors hover:bg-stone-50">
                    <td class="px-4 py-3 font-mono text-stone-500">{{ $student->registration_number }}</td>
                    <td class="px-4 py-3 font-medium text-stone-800">{{ $student->name }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @forelse($student->subjects as $subject)
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded text-xs font-mono">{{ $subject->code }}</span>
                            @empty
                                <span class="text-xs text-stone-400">None</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('students.manage.edit', $student) }}"
                               class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-600 hover:text-stone-800 rounded-lg text-xs transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('students.manage.destroy', $student) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $student->name }}? This also removes all attendance records.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-lg text-xs transition-colors border border-red-100">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-stone-400">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="flex items-center justify-between mt-4 text-sm text-stone-400">
    <span>Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</span>
    {{ $students->links('vendor.pagination.tailwind-light') }}
</div>

@endsection