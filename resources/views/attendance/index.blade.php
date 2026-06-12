@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Mark Attendance</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="subject_id">Select Subject</label>
            <select name="subject_id" id="subject_id" class="form-control" required>
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                @endforeach
            </select>
        </div>

        <div id="students-container">
            <p class="text-muted">Please select a subject to load students.</p>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Attendance</button>
    </form>
</div>

<script>
document.getElementById('subject_id').addEventListener('change', function() {
    const subjectId = this.value;
    const container = document.getElementById('students-container');

    if (!subjectId) {
        container.innerHTML = '<p class="text-muted">Please select a subject to load students.</p>';
        return;
    }

    container.innerHTML = '<p>Loading students...</p>';

    fetch(`/attendance/students?subject_id=${subjectId}`)
        .then(response => response.json())
        .then(students => {
            if (students.length === 0) {
                container.innerHTML = '<p>No students enrolled in this subject.</p>';
                return;
            }

            let html = '<table class="table"><thead><tr><th>Present</th><th>Registration No.</th><th>Name</th></tr></thead><tbody>';

            students.forEach(student => {
                html += `
                    <tr>
                        <td><input type="checkbox" name="attendance[${student.id}]" checked></td>
                        <td>${student.registration_number}</td>
                        <td>${student.name}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            container.innerHTML = html;
        })
        .catch(error => {
            container.innerHTML = '<p class="text-danger">Failed to load students.</p>';
            console.error(error);
        });
});
</script>
@endsection