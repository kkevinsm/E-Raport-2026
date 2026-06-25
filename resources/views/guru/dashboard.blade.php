@extends('layouts.app')

@section('content')
<h4 class="mb-4">Selamat Datang, Pak/Bu Guru {{ $guru->name }}!</h4>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0 fw-bold">Mata Pelajaran yang Anda Ampuh</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th class="text-center" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru->courses as $index => $course)
                    <tr>
                        <td class="ps-3 align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle fw-bold text-primary">{{ $course->name }}</td>
                        <td class="align-middle">{{ $course->academic_year ?? '-' }}</td>
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
                        <td class="align-middle">{{ $course->major->name_major ?? '-' }}</td>
                        <td class="align-middle text-center">
                            <a href="{{ route('guru.input_nilai', $course->id) }}" class="btn btn-sm btn-primary px-3">Input Nilai</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Anda belum ditugaskan mengajar mata pelajaran apa pun oleh Admin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection