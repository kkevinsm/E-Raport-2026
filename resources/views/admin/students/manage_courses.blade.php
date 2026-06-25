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
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="form-check border rounded p-3 h-100 {{ in_array($course->id, $assignedCourseIds) ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                        <input class="form-check-input"
                               type="checkbox"
                               name="course_ids[]"
                               value="{{ $course->id }}"
                               id="course_{{ $course->id }}"
                               {{ in_array($course->id, $assignedCourseIds) ? 'checked' : '' }}>
                        <label class="form-check-label w-100" for="course_{{ $course->id }}">
                            <span class="fw-bold d-block">{{ $course->name }}</span>
                            <span class="d-flex flex-wrap gap-1 mt-1">
                                @if($course->academic_year)
                                    <span class="badge bg-secondary" style="font-size:0.7rem;">{{ $course->academic_year }}</span>
                                @endif
                                @if($course->semester == 1)
                                    <span class="badge bg-info text-dark" style="font-size:0.7rem;">Semester 1</span>
                                @elseif($course->semester == 2)
                                    <span class="badge bg-warning text-dark" style="font-size:0.7rem;">Semester 2</span>
                                @endif
                                @if($course->grade)
                                    <span class="badge bg-dark" style="font-size:0.7rem;">Kelas {{ $course->grade }}</span>
                                @endif
                            </span>
                            @if($course->major)
                                <small class="text-muted d-block mt-1">{{ $course->major->name_major }}</small>
                            @endif
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