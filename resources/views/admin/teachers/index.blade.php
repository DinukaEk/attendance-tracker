@extends('layouts.custom')

@section('title', 'Manage Teachers')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-800">Manage Teachers</h1>
        <p class="mt-1 text-sm text-stone-500">Add, edit, or remove teacher accounts and subject assignments.</p>
    </div>
    <a href="{{ route('admin.teachers.create') }}"
       class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-500">
        + Add Teacher
    </a>
</div>

@if(session('success'))
    <div class="px-4 py-3 mb-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-hidden bg-white border shadow-sm border-stone-200 rounded-xl">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-xs tracking-wide uppercase border-b bg-stone-50 text-stone-400 border-stone-200">
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Assigned Subjects</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($teachers as $teacher)
                <tr class="transition-colors hover:bg-stone-50">
                    <td class="px-4 py-3 font-medium text-stone-800">{{ $teacher->name }}</td>
                    <td class="px-4 py-3 text-stone-500">{{ $teacher->email }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @forelse($teacher->subjects as $subject)
                                <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded text-xs font-mono">{{ $subject->code }}</span>
                            @empty
                                <span class="text-xs text-stone-400">None assigned</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.teachers.edit', $teacher) }}"
                               class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-600 hover:text-stone-800 rounded-lg text-xs transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $teacher->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs transition-colors border border-red-100">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-stone-400">No teachers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection