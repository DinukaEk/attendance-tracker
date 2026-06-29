@extends('layouts.custom')

@section('title', 'Manage Subjects')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-stone-800">Manage Subjects</h1>
        <p class="text-stone-500 text-sm mt-1">Add, edit, or remove subjects and assign them to teachers.</p>
    </div>
    <a href="{{ route('admin.subjects.create') }}"
       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors">
        + Add Subject
    </a>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-stone-50 text-stone-400 uppercase text-xs tracking-wide border-b border-stone-200">
                <th class="px-4 py-3 text-left">Code</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Assigned Teacher</th>
                <th class="px-4 py-3 text-center">Enrolled Students</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($subjects as $subject)
                <tr class="hover:bg-stone-50 transition-colors">
                    <td class="px-4 py-3 font-mono font-medium text-indigo-600">{{ $subject->code }}</td>
                    <td class="px-4 py-3 text-stone-800 font-medium">{{ $subject->name }}</td>
                    <td class="px-4 py-3 text-stone-500">
                        @if($subject->teacher)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                    {{ substr($subject->teacher->name, 0, 1) }}
                                </div>
                                {{ $subject->teacher->name }}
                            </div>
                        @else
                            <span class="text-stone-300">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 bg-stone-100 text-stone-600 rounded-full text-xs font-medium">
                            {{ $subject->students()->count() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.subjects.edit', $subject) }}"
                               class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-600 hover:text-stone-800 rounded-lg text-xs transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $subject->name }}? This removes all related attendance records and student enrolments.')">
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
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-stone-400">No subjects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection