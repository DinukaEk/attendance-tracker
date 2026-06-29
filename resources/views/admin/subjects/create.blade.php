@extends('layouts.custom')

@section('title', 'Add Subject')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-stone-800">Add Subject</h1>
        <p class="text-stone-500 text-sm mt-1">Create a new subject and optionally assign it to a teacher.</p>
    </div>
    <a href="{{ route('admin.subjects.index') }}"
       class="px-4 py-2 bg-white hover:bg-stone-50 text-stone-600 rounded-lg text-sm border border-stone-200 shadow-sm transition-colors">
        ← Back
    </a>
</div>

@if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-600 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.subjects.store') }}" method="POST">
    @csrf
    <div class="bg-white border border-stone-200 rounded-xl p-6 space-y-5 shadow-sm max-w-2xl">

        <div>
            <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
                Subject Name <span class="text-red-400">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="e.g. Database Systems"
                   class="w-full bg-stone-50 border border-stone-200 text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>

        <div>
            <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
                Subject Code <span class="text-red-400">*</span>
            </label>
            <input type="text" name="code" value="{{ old('code') }}" required
                   placeholder="e.g. SIT102"
                   class="w-full bg-stone-50 border border-stone-200 text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <p class="text-xs text-stone-400 mt-1">Must be unique. Used as a short identifier throughout the system.</p>
        </div>

        <div>
            <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
                Assign to Teacher <span class="text-stone-400 font-normal normal-case">(optional)</span>
            </label>
            <select name="user_id"
                    class="w-full bg-stone-50 border border-stone-200 text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <option value="">-- No Teacher Assigned --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('user_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }} ({{ $teacher->email }})
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="mt-6 flex gap-3">
        <button type="submit"
                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors">
            Create Subject
        </button>
        <a href="{{ route('admin.subjects.index') }}"
           class="px-6 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-600 rounded-lg text-sm transition-colors">
            Cancel
        </a>
    </div>
</form>

@endsection