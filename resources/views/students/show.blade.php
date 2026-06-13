@extends('layouts.custom')

@section('content')
<div class="container">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h2>{{ $student->name }} ({{ $student->registration_number }})</h2>
        <a href="{{ route('students.search') }}" class="btn btn-secondary">Back to Search</a>
    </div>

    <form method="GET" action="{{ route('students.show', $student) }}" class="mb-4 row g-3">
        <div class="col-md-3">
            <label for="date_from">From Date</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-3">
            <label for="date_to">To Date</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    <h4>Attendance Summary by Subject</h4>
    <table class="table mb-4 table-striped">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Classes Held</th>
                <th>Attended</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summary as $row)
                <tr>
                    <td>{{ $row['subject']->name }} ({{ $row['subject']->code }})</td>
                    <td>{{ $row['total'] }}</td>
                    <td>{{ $row['present'] }}</td>
                    <td>
                        @if($row['percentage'] !== null)
                            <span class="badge bg-{{ $row['percentage'] >= 75 ? 'success' : ($row['percentage'] >= 50 ? 'warning' : 'danger') }}">
                                {{ $row['percentage'] }}%
                            </span>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No subjects to display.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h4>Day-by-Day Attendance Log</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Subject</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($log as $entry)
                <tr>
                    <td>{{ $entry->date->format('Y-m-d') }}</td>
                    <td>{{ $entry->subject->name }} ({{ $entry->subject->code }})</td>
                    <td>
                        @if($entry->present)
                            <span class="badge bg-success">Present</span>
                        @else
                            <span class="badge bg-danger">Absent</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $log->links() }}
    </div>
</div>
@endsection