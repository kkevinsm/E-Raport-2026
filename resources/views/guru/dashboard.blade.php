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
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru->courses as $index => $course)
                    <tr>
                        <td class="ps-3 align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle fw-bold text-primary">{{ $course->name }}</td>
                        <td class="align-middle text-center">
                            <a href="{{ route('guru.input_nilai', $course->id) }}" class="btn btn-sm btn-primary px-3">Input Nilai</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">
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