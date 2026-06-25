@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
    <h5 class="mb-0 fw-bold">Detail Guru: {{ $user->name }}</h5>
    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-secondary">&larr; Kembali ke Daftar Pengguna</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Informasi Guru</h6>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Nama Lengkap:</strong> {{ $user->name }}</p>
                <p class="mb-0"><strong>NBM / Username:</strong> {{ $user->username }}</p>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Tugaskan Mata Pelajaran</h6>
            </div>
            <div class="card-body">
                
                <form action="{{ route('admin.gurus.assign_course', $user->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih Mata Pelajaran</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Pilih dari Dropdown --</option>
                            @forelse($availableCourses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name }}
                                    @if($course->academic_year) — {{ $course->academic_year }} @endif
                                    @if($course->semester) · Smt {{ $course->semester }} @endif
                                    @if($course->grade) · Kls {{ $course->grade }} @endif
                                    @if($course->major) · {{ $course->major->name_major }} @endif
                                </option>
                            @empty
                                <option value="" disabled>Semua mapel sudah diampuh guru ini</option>
                            @endforelse
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" {{ $availableCourses->isEmpty() ? 'disabled' : '' }}>
                        Tambahkan Mapel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">Daftar Mata Pelajaran yang Diampuh</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No</th>
                                <th>Mata Pelajaran</th>
                                <th>Tahun Ajaran</th>
                                <th>Semester</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th class="text-center" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->courses as $index => $course)
                            <tr>
                                <td class="ps-3 align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle">
                                    <span class="fw-bold text-primary">{{ $course->name }}</span>
                                </td>
                                <td class="align-middle">
                                    {{ $course->academic_year ?? '-' }}
                                </td>
                                <td class="align-middle">
                                    @if($course->semester == 1)
                                        <span class="badge bg-info text-dark">Semester 1</span>
                                    @elseif($course->semester == 2)
                                        <span class="badge bg-warning text-dark">Semester 2</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($course->grade)
                                        <span class="badge bg-secondary">Kelas {{ $course->grade }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    {{ $course->major->name_major ?? '-' }}
                                </td>
                                <td class="align-middle text-center">
                                    <form action="{{ route('admin.gurus.remove_course', ['user' => $user->id, 'course' => $course->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin melepas mata pelajaran ini dari guru tersebut?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3">Lepas</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Guru ini belum mengampuh mata pelajaran apa pun.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection