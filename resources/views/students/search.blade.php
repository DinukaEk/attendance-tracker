@extends('layouts.custom')

@section('content')
<div class="container">
    <h2>Search Student</h2>

    <form method="GET" action="{{ route('students.search') }}" class="mb-4 row g-3">
        <div class="col-md-6 position-relative">
            <input type="text" name="q" id="search-input" class="form-control" placeholder="Search by name or registration number..." value="{{ $query }}" autofocus autocomplete="off">
            <div id="suggestions" class="list-group position-absolute w-100" style="z-index: 1000; display: none;"></div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    @if($query)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Registration No.</th>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->registration_number }}</td>
                        <td>{{ $student->name }}</td>
                        <td><a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-primary">View Attendance</a></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>

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
                        suggestionsBox.innerHTML = '';
                        return;
                    }

                    suggestionsBox.innerHTML = students.map(s => `
                        <a href="/students/${s.id}" class="list-group-item list-group-item-action">
                            <strong>${s.registration_number}</strong> - ${s.name}
                        </a>
                    `).join('');
                    suggestionsBox.style.display = 'block';
                })
                .catch(() => {
                    suggestionsBox.style.display = 'none';
                });
        }, 250); // debounce 250ms
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.style.display = 'none';
        }
    });

    // Hide suggestions on Escape
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            suggestionsBox.style.display = 'none';
        }
    });
});
</script>
@endsection