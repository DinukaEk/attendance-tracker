@extends('layouts.custom')

@section('content')
<div class="container">
    <h2>Add Teacher</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.teachers.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Assign Subjects</label>
            @foreach($subjects as $subject)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="subjects[]" value="{{ $subject->id }}" id="subject_{{ $subject->id }}">
                    <label class="form-check-label" for="subject_{{ $subject->id }}">
                        {{ $subject->name }} ({{ $subject->code }})
                        @if($subject->user_id)
                            <span class="text-muted">- currently: {{ $subject->teacher->name ?? 'Unassigned' }}</span>
                        @endif
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Create Teacher</button>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection