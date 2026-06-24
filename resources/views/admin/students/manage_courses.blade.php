@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h5 class="fw-bold mb-0">Atur Mapel: {{ $student->user->name }}</h5>
    <a href="{{ route('admin.students') }}" class="btn btn-secondary">&larr; Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">Pilih Mata Pelajaran</div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.students.update_courses', $student->id) }}" method="POST">
            @csrf
            <div class="row">
                @foreach($allCourses as $course)
                <div class="col-md-4 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="course_{{ $course->id }}" 
                            {{ in_array($course->id, $assignedCourseIds) ? 'checked' : '' }}>
                        <label class="form-check-label" for="course_{{ $course->id }}">
                            {{ $course->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            <hr>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Mata Pelajaran</button>
        </form>
    </div>
</div>
@endsection