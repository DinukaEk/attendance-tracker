@extends('layouts.custom')

@section('title', 'Mark Attendance')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800">Mark Attendance</h1>
    <p class="mt-1 text-sm text-stone-500">Select a subject to load enrolled students and mark today's attendance.</p>
</div>

@if(session('success'))
    <div class="px-4 py-3 mb-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('attendance.store') }}" method="POST">
    @csrf

    <div class="p-6 mb-6 bg-white border shadow-sm border-stone-200 rounded-xl">
        <label class="block mb-2 text-xs font-medium tracking-wide uppercase text-stone-500">Select Subject</label>
        <select name="subject_id" id="subject_id" required
                class="w-full px-3 py-2 text-sm border rounded-lg md:w-80 bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">-- Select Subject --</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
            @endforeach
        </select>
    </div>

    <div id="students-container">
        <div class="p-8 text-sm text-center bg-white border shadow-sm border-stone-200 rounded-xl text-stone-400">
            Please select a subject to load students.
        </div>
    </div>

    <div id="submit-section" class="hidden mt-6">
        <button type="submit"
                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors">
            Save Attendance
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('subject_id');
    const container = document.getElementById('students-container');
    const submitSection = document.getElementById('submit-section');

    select.addEventListener('change', function () {
        const subjectId = this.value;

        if (!subjectId) {
            container.innerHTML = `
                <div class="p-8 text-sm text-center bg-white border shadow-sm border-stone-200 rounded-xl text-stone-400">
                    Please select a subject to load students.
                </div>`;
            submitSection.classList.add('hidden');
            return;
        }

        container.innerHTML = `
            <div class="p-8 text-sm text-center bg-white border shadow-sm border-stone-200 rounded-xl text-stone-400 animate-pulse">
                Loading students...
            </div>`;

        fetch(`/attendance/students?subject_id=${subjectId}`)
            .then(res => res.json())
            .then(students => {
                if (students.length === 0) {
                    container.innerHTML = `
                        <div class="p-8 text-sm text-center bg-white border shadow-sm border-stone-200 rounded-xl text-stone-400">
                            No students enrolled in this subject.
                        </div>`;
                    submitSection.classList.add('hidden');
                    return;
                }

                let html = `
                    <div class="overflow-hidden bg-white border shadow-sm border-stone-200 rounded-xl">
                        <div class="flex items-center justify-between px-4 py-3 border-b bg-stone-50 border-stone-200">
                            <span class="text-xs font-medium tracking-wide uppercase text-stone-400">${students.length} Students</span>
                            <div class="flex gap-3">
                                <button type="button" onclick="markAll(true)" class="text-xs font-medium transition-colors text-emerald-600 hover:text-emerald-500">Mark All Present</button>
                                <button type="button" onclick="markAll(false)" class="text-xs font-medium text-red-500 transition-colors hover:text-red-400">Mark All Absent</button>
                            </div>
                        </div>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs tracking-wide uppercase border-b border-stone-100 text-stone-400">
                                    <th class="px-4 py-3 text-left">Present</th>
                                    <th class="px-4 py-3 text-left">Reg. No.</th>
                                    <th class="px-4 py-3 text-left">Name</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">`;

                students.forEach(student => {
                    html += `
                        <tr class="transition-colors hover:bg-stone-50">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="attendance[${student.id}]"
                                       class="w-4 h-4 cursor-pointer attendance-check accent-indigo-600" checked>
                            </td>
                            <td class="px-4 py-3 font-mono text-stone-500">${student.registration_number}</td>
                            <td class="px-4 py-3 font-medium text-stone-800">${student.name}</td>
                        </tr>`;
                });

                html += `</tbody></table></div>`;
                container.innerHTML = html;
                submitSection.classList.remove('hidden');
            })
            .catch(() => {
                container.innerHTML = `
                    <div class="p-4 text-sm text-red-600 border border-red-200 bg-red-50 rounded-xl">
                        Failed to load students. Please try again.
                    </div>`;
                submitSection.classList.add('hidden');
            });
    });

    window.markAll = function(present) {
        document.querySelectorAll('.attendance-check').forEach(cb => cb.checked = present);
    };
});
</script>

@endsection