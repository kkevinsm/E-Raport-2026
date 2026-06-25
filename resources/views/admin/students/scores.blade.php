@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
    <h5 class="fw-bold mb-0">Manajemen Nilai: {{ $student->user->name }}</h5>
    <a href="{{ route('admin.students') }}" class="btn btn-secondary">&larr; Kembali</a>
</div>

{{-- PANEL EXPORT PDF DENGAN FILTER SEMESTER --}}
@if($student->scores->count() > 0)
<div class="card shadow-sm mb-4 border-success">
    <div class="card-header bg-success bg-opacity-10 fw-bold text-success">Export Rapor PDF</div>
    <div class="card-body">
        <form action="{{ route('export.pdf', $student->id) }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun Ajaran</label>
                <select name="academic_year" class="form-select" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Semester</label>
                <select name="semester" class="form-select" required>
                    <option value="">-- Pilih Semester --</option>
                    <option value="1">Semester 1 (Ganjil)</option>
                    <option value="2">Semester 2 (Genap)</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100">
                    Export PDF
                </button>
            </div>
        </form>
    </div>
</div>
@endif


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
                                <option value="{{ $course->id }}">
                                    {{ $course->name }}
                                    @if($course->academic_year) — {{ $course->academic_year }} @endif
                                    @if($course->semester) · Smt {{ $course->semester }} @endif
                                </option>
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
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Kategori</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($student->scores as $score)
                    <tr>
                        <td class="ps-3">{{ $score->course->name }}</td>
                        <td>{{ $score->course->academic_year ?? '-' }}</td>
                        <td>
                            @if($score->course->semester == 1)
                                <span class="badge bg-info text-dark">Semester 1</span>
                            @elseif($score->course->semester == 2)
                                <span class="badge bg-warning text-dark">Semester 2</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $score->category->name }}</td>
                        <td class="fw-bold text-primary">{{ $score->score }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-muted">Belum ada nilai yang diinput.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection