@extends('layouts.custom')

@section('title', $student->name)

@section('content')

<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-stone-800">{{ $student->name }}</h1>
        <p class="mt-1 font-mono text-sm text-stone-400">{{ $student->registration_number }}</p>
    </div>
    <a href="{{ route('students.search') }}"
       class="px-4 py-2 text-sm transition-colors bg-white border rounded-lg shadow-sm hover:bg-stone-50 text-stone-600 border-stone-200">
        ← Back to Search
    </a>
</div>

<form method="GET" action="{{ route('students.show', $student) }}"
      class="flex flex-wrap items-end gap-4 p-4 mb-6 bg-white border shadow-sm border-stone-200 rounded-xl">
    <div class="flex flex-col gap-1">
        <label class="text-xs font-medium text-stone-500">From Date</label>
        <input type="date" name="date_from" value="{{ $dateFrom }}"
               class="px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-medium text-stone-500">To Date</label>
        <input type="date" name="date_to" value="{{ $dateTo }}"
               class="px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
    </div>
    <button type="submit"
            class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-500">
        Filter
    </button>
</form>

<h2 class="mb-3 text-xs font-semibold tracking-wide uppercase text-stone-400">Attendance Summary by Subject</h2>
<div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-2 lg:grid-cols-3">
    @forelse($summary as $row)
        @php
            $pct = $row['percentage'];
            $cardColor = $pct === null ? 'border-stone-200' : ($pct >= 75 ? 'border-emerald-200' : ($pct >= 50 ? 'border-amber-200' : 'border-red-200'));
            $badgeColor = $pct === null ? '' : ($pct >= 75 ? 'bg-emerald-100 text-emerald-700' : ($pct >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700'));
            $barColor = $pct === null ? '' : ($pct >= 75 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-400' : 'bg-red-400'));
        @endphp
        <div class="bg-white border {{ $cardColor }} rounded-xl p-4 shadow-sm">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <div class="text-sm font-medium text-stone-800">{{ $row['subject']->name }}</div>
                    <div class="text-stone-400 text-xs font-mono mt-0.5">{{ $row['subject']->code }}</div>
                </div>
                @if($pct !== null)
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">{{ $pct }}%</span>
                @else
                    <span class="text-xs text-stone-400">N/A</span>
                @endif
            </div>
            <div class="flex gap-4 mb-3 text-xs text-stone-400">
                <span>{{ $row['total'] }} classes held</span>
                <span>{{ $row['present'] }} attended</span>
            </div>
            @if($pct !== null)
                <div class="w-full bg-stone-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full {{ $barColor }}" style="width: {{ min($pct, 100) }}%"></div>
                </div>
            @endif
        </div>
    @empty
        <div class="col-span-3 py-6 text-center text-stone-400">No subjects to display.</div>
    @endforelse
</div>

<h2 class="mb-3 text-xs font-semibold tracking-wide uppercase text-stone-400">Day-by-Day Attendance Log</h2>
<div class="overflow-hidden bg-white border shadow-sm border-stone-200 rounded-xl">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-xs tracking-wide uppercase border-b bg-stone-50 text-stone-400 border-stone-200">
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Subject</th>
                <th class="px-4 py-3 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($log as $entry)
                <tr class="transition-colors hover:bg-stone-50">
                    <td class="px-4 py-3 font-mono text-stone-500">{{ $entry->date->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 text-stone-800">
                        {{ $entry->subject->name }}
                        <span class="ml-1 text-xs text-stone-400">({{ $entry->subject->code }})</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($entry->present)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Present</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Absent</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-stone-400">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="flex justify-center mt-4">
    {{ $log->links('vendor.pagination.tailwind-light') }}
</div>

@endsection