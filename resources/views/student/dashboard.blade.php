@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold">Laporan Hasil Ujian (E-Raport)</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-3">No</th>
                    <th>Mata Pelajaran</th>
                    <th class="text-center">Nilai</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($student->scores as $index => $score)
                <tr>
                    <td class="ps-3">{{ $index + 1 }}</td>
                    <td>{{ $score->course->name }}</td>
                    <td class="text-center fw-bold">{{ $score->score }}</td>
                    <td class="text-center">
                        @if($score->score >= 75)
                            <span class="badge bg-success">Tuntas</span>
                        @else
                            <span class="badge bg-danger">Belum Tuntas</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Nilai belum tersedia. Silakan hubungi Guru Anda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection