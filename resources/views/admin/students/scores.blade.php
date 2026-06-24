@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h5 class="fw-bold mb-0">Manajemen Nilai: {{ $student->user->name }}</h5>
    
    <div class="d-flex flex-wrap gap-2">
        @if($student->scores->count() > 0)
            <a href="{{ route('export.pdf', $student->id) }}" class="btn btn-success">
                Export PDF
            </a>
        @endif
        <a href="{{ route('admin.students') }}" class="btn btn-secondary">&larr; Kembali</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">Input Nilai</div>
    <div class="card-body">
        
        {{-- CEK APAKAH SISWA INI PUNYA MAPEL YANG BISA DINILAI OLEH USER YANG LOGIN --}}
        @if($courses->count() > 0)
            <form action="{{ route('admin.students.scores.store', $student->id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kategori Nilai</label>
                        <select name="score_category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(\App\Models\ScoreCategory::all() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nilai (0-100)</label>
                        <input type="number" name="score" class="form-control" placeholder="Masukkan nilai" required min="0" max="100">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Simpan Nilai</button>
                    </div>
                </div>
            </form>
        @else
            {{-- JIKA KOSONG, TAMPILKAN PERINGATAN INI --}}
            <div class="alert alert-warning mb-0 text-center">
                Siswa ini belum memiliki Mata Pelajaran yang diatur, atau Anda tidak memiliki akses untuk memberikan nilai pada mata pelajaran siswa ini. 
                <br> <small>(Silakan Admin mengatur mapel siswa di menu "Atur Mapel")</small>
            </div>
        @endif

    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">Riwayat Nilai Siswa</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Mata Pelajaran</th>
                        <th>Kategori</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->scores as $score)
                    <tr>
                        <td class="ps-3">{{ $score->course->name }}</td>
                        <td>{{ $score->category->name }}</td>
                        <td class="fw-bold text-primary">{{ $score->score }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-3 text-muted">Belum ada nilai yang diinput.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection