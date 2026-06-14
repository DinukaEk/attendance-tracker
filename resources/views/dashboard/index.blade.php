@extends('layouts.custom')

@section('title', 'Dashboard')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800">Attendance Dashboard</h1>
    <p class="mt-1 text-sm text-stone-500">Overview of student attendance within the selected date range.</p>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('dashboard') }}"
      class="flex flex-wrap items-end gap-4 p-4 mb-6 bg-white border shadow-sm rounded-xl border-stone-200">
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
    <div class="flex flex-col gap-1">
        <label class="text-xs font-medium text-stone-500">Subject</label>
        <select name="subject_id"
                class="px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">All Subjects</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ (string)$subjectId === (string)$subject->id ? 'selected' : '' }}>
                    {{ $subject->name }} ({{ $subject->code }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-xs font-medium text-stone-500">Per Page</label>
        <select name="per_page"
                class="px-3 py-2 text-sm border rounded-lg bg-stone-50 border-stone-200 text-stone-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            @foreach([25, 50, 100, 200] as $size)
                <option value="{{ $size }}" {{ request('per_page', 50) == $size ? 'selected' : '' }}>{{ $size }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-500">
        Filter
    </button>
    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium transition-colors rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-600">
        Reset
    </a>
</form>

{{-- Chart --}}
@php
    $chartLabels = $results->pluck('name')->toArray();
    $chartData = $results->pluck('percentage')->map(fn($p) => $p ?? 0)->toArray();
@endphp

<div class="p-4 mb-6 bg-white border shadow-sm border-stone-200 rounded-xl">
    <h2 class="mb-3 text-xs font-semibold tracking-wide uppercase text-stone-400">Attendance % — Current Page</h2>
    <canvas id="attendanceChart" height="80"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Attendance %',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.15)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#78716c' } } },
            scales: {
                x: { ticks: { color: '#a8a29e' }, grid: { color: '#f5f5f4' } },
                y: {
                    min: 0, max: 100,
                    ticks: { color: '#a8a29e', callback: v => v + '%' },
                    grid: { color: '#f5f5f4' }
                }
            }
        }
    });
});
</script>

{{-- Table --}}
<div class="overflow-hidden bg-white border shadow-sm border-stone-200 rounded-xl">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-xs tracking-wide uppercase border-b bg-stone-50 text-stone-400 border-stone-200">
                <th class="px-4 py-3 text-left">Reg. No.</th>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-center">Classes Held</th>
                <th class="px-4 py-3 text-center">Attended</th>
                <th class="px-4 py-3 text-center">Attendance %</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            @forelse($results as $row)
                <tr class="transition-colors hover:bg-stone-50">
                    <td class="px-4 py-3 font-mono text-stone-500">{{ $row->registration_number }}</td>
                    <td class="px-4 py-3 font-medium text-stone-800">{{ $row->name }}</td>
                    <td class="px-4 py-3 text-center text-stone-600">{{ $row->total_classes }}</td>
                    <td class="px-4 py-3 text-center text-stone-600">{{ $row->attended }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($row->percentage !== null)
                            @php
                                $color = $row->percentage >= 75
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : ($row->percentage >= 50
                                        ? 'bg-amber-100 text-amber-700'
                                        : 'bg-red-100 text-red-700');
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                {{ $row->percentage }}%
                            </span>
                        @else
                            <span class="text-stone-400">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-stone-400">No records found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="flex items-center justify-between mt-4 text-sm text-stone-400">
    <span>Showing {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }} of {{ $results->total() }} students</span>
    {{ $results->links('vendor.pagination.tailwind-light') }}
</div>

@endsection