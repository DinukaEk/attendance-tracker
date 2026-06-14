@extends('layouts.custom')

@section('title', 'Search Student')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800">Search Student</h1>
    <p class="mt-1 text-sm text-stone-500">Find a student by name or registration number to view their attendance.</p>
</div>

<form method="GET" action="{{ route('students.search') }}" class="mb-6">
    <div class="relative w-full md:w-96">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </div>
        <input type="text" name="q" id="search-input" value="{{ $query }}"
               placeholder="Search by name or registration number..."
               autocomplete="off"
               class="w-full bg-white border border-stone-200 text-stone-800 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 placeholder-stone-400 shadow-sm">
        <div id="suggestions"
             class="absolute z-50 w-full mt-1 overflow-hidden bg-white border shadow-lg border-stone-200 rounded-xl"
             style="display:none;"></div>
    </div>
</form>

@if($query)
    <div class="overflow-hidden bg-white border shadow-sm border-stone-200 rounded-xl">
        <div class="px-4 py-3 border-b bg-stone-50 border-stone-200">
            <span class="text-xs font-medium tracking-wide uppercase text-stone-400">
                Results for "{{ $query }}"
            </span>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-xs tracking-wide uppercase border-b text-stone-400 border-stone-100">
                    <th class="px-4 py-3 text-left">Reg. No.</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($students as $student)
                    <tr class="transition-colors hover:bg-stone-50">
                        <td class="px-4 py-3 font-mono text-stone-500">{{ $student->registration_number }}</td>
                        <td class="px-4 py-3 font-medium text-stone-800">{{ $student->name }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('students.show', $student) }}"
                               class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-xs font-medium transition-colors">
                                View Attendance
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-stone-400">No students found for "{{ $query }}".</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('suggestions');
    let debounceTimer;

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 1) {
            suggestionsBox.style.display = 'none';
            suggestionsBox.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('students.suggestions') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(students => {
                    if (students.length === 0) {
                        suggestionsBox.style.display = 'none';
                        return;
                    }
                    suggestionsBox.innerHTML = students.map(s => `
                        <a href="/students/${s.id}"
                           class="flex items-center gap-3 px-4 py-2.5 hover:bg-stone-50 transition-colors border-b border-stone-100 last:border-0">
                            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-xs font-bold text-indigo-600 bg-indigo-100 rounded-full">
                                ${s.name.charAt(0)}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-stone-800">${s.name}</div>
                                <div class="font-mono text-xs text-stone-400">${s.registration_number}</div>
                            </div>
                        </a>
                    `).join('');
                    suggestionsBox.style.display = 'block';
                });
        }, 250);
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') suggestionsBox.style.display = 'none';
    });
});
</script>

@endsection