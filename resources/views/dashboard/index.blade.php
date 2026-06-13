@extends('layouts.custom')

@section('content')
<div class="container">
    <h2>Attendance Dashboard</h2>

    <canvas id="attendanceChart" height="100"></canvas>

    @php
        $chartLabels = $results->pluck('name')->toArray();
        $chartData = $results->pluck('percentage')->map(fn($p) => $p ?? 0)->toArray();
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('attendanceChart');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Attendance %',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: '#4f46e5'
                    }]
                },
                options: { responsive: true }
            });
        });
    </script>

    <br/><hr/><br/>

    <form method="GET" action="{{ route('dashboard') }}" class="mb-4 row g-3">
        <div class="col-md-3">
            <label for="date_from">From Date</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>

        <div class="col-md-3">
            <label for="date_to">To Date</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $dateTo }}">
        </div>

        <div class="col-md-3">
            <label for="subject_id">Subject</label>
            <select name="subject_id" id="subject_id" class="form-control">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ (string)$subjectId === (string)$subject->id ? 'selected' : '' }}>
                        {{ $subject->name }} ({{ $subject->code }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="mb-2 d-flex justify-content-between align-items-center">
        <div>
            Showing {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }} of {{ $results->total() }} students
        </div>
        <form method="GET" action="{{ route('dashboard') }}" class="gap-2 d-flex align-items-center">
            <input type="hidden" name="date_from" value="{{ $dateFrom }}">
            <input type="hidden" name="date_to" value="{{ $dateTo }}">
            <input type="hidden" name="subject_id" value="{{ $subjectId }}">
            <label for="per_page" class="mb-0">Per page:</label>
            <select name="per_page" id="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                @foreach([25, 50, 100, 200] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 50) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Registration No.</th>
                <th>Name</th>
                <th>Classes Held</th>
                <th>Classes Attended</th>
                <th>Attendance %</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $row)
                <tr>
                    <td>{{ $row->registration_number }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->total_classes }}</td>
                    <td>{{ $row->attended }}</td>
                    <td>
                        @if($row->percentage !== null)
                            <span class="badge bg-{{ $row->percentage >= 75 ? 'success' : ($row->percentage >= 50 ? 'warning' : 'danger') }}">
                                {{ $row->percentage }}%
                            </span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No records found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $results->links() }}
</div>
@endsection