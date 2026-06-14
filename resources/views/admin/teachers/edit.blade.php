@extends('layouts.custom')

@section('title', 'Edit Teacher')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-800">Edit Teacher</h1>
        <p class="mt-1 text-sm text-stone-500">{{ $teacher->email }}</p>
    </div>
    <a href="{{ route('admin.teachers.index') }}"
       class="px-4 py-2 text-sm transition-colors bg-white border rounded-lg shadow-sm hover:bg-stone-50 text-stone-600 border-stone-200">
        ← Back
    </a>
</div>

@if($errors->any())
    <div class="px-4 py-3 mb-4 text-sm text-red-600 border border-red-200 rounded-lg bg-red-50">
        <ul class="space-y-1 list-disc list-inside">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
    @csrf
    @method('PUT')
    @php $assignedIds = $teacher->subjects->pluck('id')->toArray(); @endphp
    <div class="p-6 space-y-5 bg-white border shadow-sm border-stone-200 rounded-xl">
        <div>
            <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required
                   class="w-full px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <div>
            <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">Email <span class="text-red-400">*</span></label>
            <input type="email" name="email" value="{{ old('email', $teacher->email) }}" required
                   class="w-full px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <div>
            <label class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
                Password <span class="font-normal normal-case text-stone-400">(leave blank to keep current)</span>
            </label>
            <input type="password" name="password"
                   class="w-full px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        </div>
        <div>
            <label class="block mb-3 text-xs font-medium tracking-wide uppercase text-stone-500">Assign Subjects</label>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                @foreach($subjects as $subject)
                    @php $checked = in_array($subject->id, old('subjects', $assignedIds)); @endphp
                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors
                                  {{ $checked ? 'bg-indigo-50 border-indigo-300' : 'bg-stone-50 border-stone-200 hover:border-stone-300' }}">
                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                               class="w-4 h-4 accent-indigo-600" {{ $checked ? 'checked' : '' }}
                               onchange="this.closest('label').className = this.checked
                                   ? 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors bg-indigo-50 border-indigo-300'
                                   : 'flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors bg-stone-50 border-stone-200 hover:border-stone-300'">
                        <div>
                            <div class="text-sm font-medium text-stone-800">{{ $subject->name }}</div>
                            <div class="font-mono text-xs text-stone-400">{{ $subject->code }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="flex gap-3 mt-6">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors">
            Update Teacher
        </button>
        <a href="{{ route('admin.teachers.index') }}" class="px-6 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-600 rounded-lg text-sm transition-colors">
            Cancel
        </a>
    </div>
</form>

@endsection