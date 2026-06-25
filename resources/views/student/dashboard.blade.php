@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Student Information Summary Card -->
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #1b68cf 0%, #0d4b9f 100%);">
            <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <span class="badge bg-white bg-opacity-20 text-white mb-2 px-3 py-2 rounded-pill fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        {{ Auth::user()->role }} Account
                    </span>
                    <h3 class="fw-bold mb-1">{{ $student->user->name }}</h3>
                    <p class="mb-0 text-white text-opacity-80">
                        NIS: <strong class="text-white">{{ $student->nis }}</strong> &nbsp;|&nbsp; 
                        NISN: <strong class="text-white">{{ $student->nisn ?? '-' }}</strong>
                    </p>
                </div>
                <div class="border-start border-white border-opacity-25 ps-md-4 py-1 text-md-end">
                    <p class="mb-1 text-white text-opacity-70 fw-medium">Kelas & Jurusan</p>
                    <h5 class="fw-bold mb-0">
                        {{ $student->class_name }} 
                    </h5>
                    <small class="text-white text-opacity-80 fw-semibold">{{ $student->major->name_major ?? '-' }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Filter Section -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body p-4">
                <form action="{{ route('student.dashboard') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">Tahun Ajaran</label>
                        <select name="academic_year" class="form-select" style="border-radius: 8px;" required>
                            <option value="">-- Pilih Tahun Ajaran --</option>
                            @php
                                $uniqueYears = $periods->pluck('academic_year')->unique();
                            @endphp
                            @foreach($uniqueYears as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">Semester</label>
                        <select name="semester" class="form-select" style="border-radius: 8px;" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="1" {{ $selectedSemester == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ $selectedSemester == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold" style="border-radius: 8px; padding-top: 0.6rem; padding-bottom: 0.6rem;">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        @if($selectedYear && $selectedSemester)
                            <a href="{{ route('student.export_pdf', ['academic_year' => $selectedYear, 'semester' => $selectedSemester]) }}" 
                               class="btn btn-outline-danger w-100 fw-semibold d-flex align-items-center justify-content-center gap-1" 
                               style="border-radius: 8px; padding-top: 0.6rem; padding-bottom: 0.6rem;" 
                               title="Download PDF Rapor">
                                <i class="bi bi-file-pdf-fill fs-5"></i>
                                <span>PDF</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
        <div>
            <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-card-checklist me-2 text-primary"></i>Laporan Hasil Belajar</h6>
            @if($selectedYear && $selectedSemester)
                <small class="text-muted">Tahun Ajaran: <strong>{{ $selectedYear }}</strong> | {{ $selectedSemester == 1 ? 'Semester 1 (Ganjil)' : 'Semester 2 (Genap)' }}</small>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; width: 60px;">No</th>
                        <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; width: 300px;">Mata Pelajaran</th>
                        <th class="text-center text-primary fw-bold text-uppercase" style="font-size: 0.8rem; width: 130px;">Nilai Akhir</th>
                        <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem;">Capaian Pembelajaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $index => $course)
                        @php
                            $total = 0;
                            $count = 0;
                            foreach($categories as $category) {
                                $score = $student->scores->where('course_id', $course->id)
                                                         ->where('score_category_id', $category->id)->first();
                                $val = $score ? $score->score : null;
                                if ($val !== null) {
                                    $total += $val;
                                    $count++;
                                }
                            }
                            $finalScore = $count > 0 ? $total / $count : 0;
                            $cp = $student->capaianPembelajaran->where('course_id', $course->id)->first();
                        @endphp
                        <tr>
                            <td class="ps-4 fw-medium">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $course->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    Tingkat: {{ $course->grade }} | Kelas: {{ $course->major->name_major ?? '-' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 fs-6" style="border-radius: 8px;">
                                    {{ $count > 0 ? number_format($finalScore, 1) : '-' }}
                                </span>
                            </td>
                            <td>
                                @if($cp && !empty($cp->description))
                                    <div class="text-dark fw-normal" style="font-size: 0.9rem; line-height: 1.5; text-align: justify; max-width: 800px; white-space: normal;">
                                        {{ $cp->description }}
                                    </div>
                                @else
                                    <span class="text-muted fst-italic" style="font-size: 0.9rem;">Belum ada deskripsi capaian pembelajaran.</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="mb-3">
                                    <i class="bi bi-journal-x fs-1 text-secondary opacity-50"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Data Nilai Tidak Ditemukan</h6>
                                <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                    @if($selectedYear && $selectedSemester)
                                        Belum ada nilai atau mata pelajaran yang terdaftar pada Tahun Ajaran {{ $selectedYear }} (Semester {{ $selectedSemester == 1 ? 'Ganjil' : 'Genap' }}).
                                    @else
                                        Anda belum memiliki riwayat kelas atau mata pelajaran yang diatur. Silakan hubungi admin.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection