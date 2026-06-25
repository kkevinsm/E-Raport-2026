@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Input Nilai Siswa</h5>
        <p class="text-muted mb-0">Mata Pelajaran: <strong>{{ $course->name }}</strong></p>
    </div>
    <a href="{{ route('guru.dashboard') }}" class="btn btn-sm btn-secondary">&larr; Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        @if($categories->isEmpty())
            <div class="alert alert-warning">
                <strong>Admin belum membuat Kategori Nilai (Kolom Nilai).</strong><br>
                Silakan hubungi Admin untuk menambahkan kolom penilaian terlebih dahulu.
            </div>
        @else
            <form action="{{ route('guru.save_nilai', $course->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover table-bordered w-100" style="vertical-align: middle;">
                        <thead class="table-light text-center">
                            <tr>
                                <th class="align-middle" style="width: 50px;">No</th>
                                <th class="align-middle text-start">Nama Siswa</th>
                                
                                @foreach($categories as $category)
                                    <th style="width: 120px;">{{ $category->name }}</th>
                                @endforeach
                                
                                <th class="bg-primary text-white align-middle" style="width: 120px;">Nilai Akhir</th>
                                <th class="align-middle text-center" style="width: 100px;">CP</th>
                                <th class="align-middle text-center" style="width: 100px;">Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr class="student-row">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $student->user->name }}</strong><br>
                                    <small class="text-muted">{{ $student->nis }}</small>
                                </td>
                                
                                @foreach($categories as $category)
                                    @php
                                        // Mencari nilai spesifik untuk kategori ini jika sudah pernah disimpan
                                        $existingScore = $student->scores->where('score_category_id', $category->id)->first();
                                    @endphp
                                    <td>
                                        <input type="number" 
                                               name="scores[{{ $student->id }}][{{ $category->id }}]" 
                                               class="form-control form-control-sm text-center score-input" 
                                               placeholder="0" 
                                               min="0" max="100" step="0.1"
                                               value="{{ $existingScore->score ?? '' }}">
                                    </td>
                                @endforeach
                                
                                <td class="text-center bg-light">
                                    <h5 class="mb-0 fw-bold text-primary average-display">0</h5>
                                </td>
                                
                                @php
                                    $existingCp = $student->capaianPembelajaran->where('course_id', $course->id)->first();
                                    $hasCp = !empty($existingCp->description);
                                @endphp
                                <td class="text-center align-middle">
                                    <button type="button" 
                                            class="btn btn-sm {{ $hasCp ? 'btn-success' : 'btn-outline-primary' }} cp-btn fw-semibold px-3" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cpModal{{ $student->id }}" 
                                            style="border-radius: 6px;">
                                        <i class="bi {{ $hasCp ? 'bi-chat-left-check-fill' : 'bi-chat-left-text' }} me-1"></i> CP
                                    </button>

                                    <!-- Modal CP for Student -->
                                    <div class="modal fade text-start" id="cpModal{{ $student->id }}" tabindex="-1" aria-labelledby="cpModalLabel{{ $student->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="modal-title fw-bold text-dark" id="cpModalLabel{{ $student->id }}">
                                                        Capaian Pembelajaran
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p class="text-muted mb-3">Siswa: <strong>{{ $student->user->name }}</strong> ({{ $student->nis }})<br>Mata Pelajaran: <strong>{{ $course->name }}</strong></p>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold text-secondary">Deskripsi Capaian Pembelajaran</label>
                                                        <textarea name="capaian[{{ $student->id }}]" 
                                                                  class="form-control capaian-textarea" 
                                                                  rows="6" 
                                                                  placeholder="Masukkan deskripsi capaian pembelajaran siswa..." 
                                                                  style="border-radius: 10px;">{{ $existingCp->description ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                                                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal" style="background-color: #1b68cf; border-color: #1b68cf; border-radius: 8px;">Oke</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center align-middle">
                                    <a href="{{ route('export.pdf', $student->id) }}" class="btn btn-sm btn-outline-danger px-2" style="border-radius: 6px;" title="Cetak Rapor PDF">
                                        <i class="bi bi-file-pdf-fill"></i> PDF
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Semua Nilai & CP</button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const studentRows = document.querySelectorAll('.student-row');

        studentRows.forEach(row => {
            const inputs = row.querySelectorAll('.score-input');
            const averageDisplay = row.querySelector('.average-display');

            // Fungsi Kalkulasi
            const calculateAverage = () => {
                let total = 0;
                let count = 0;

                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (!isNaN(value)) {
                        total += value;
                        count++;
                    }
                });

                // Tampilkan nilai akhir (hanya hitung kolom yang sudah diisi)
                if (count > 0) {
                    const average = (total / count).toFixed(1);
                    averageDisplay.textContent = average;
                } else {
                    averageDisplay.textContent = "0";
                }
            };

            // Hitung saat halaman pertama kali dimuat
            calculateAverage();

            // Hitung secara realtime setiap kali guru mengetik angka
            inputs.forEach(input => {
                input.addEventListener('input', calculateAverage);
            });
        });

        // Dynamic styling for CP button
        const textareas = document.querySelectorAll('.capaian-textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                const match = this.name.match(/capaian\[(\d+)\]/);
                if (match) {
                    const studentId = match[1];
                    const btn = document.querySelector(`button[data-bs-target="#cpModal${studentId}"]`);
                    if (btn) {
                        const icon = btn.querySelector('i');
                        if (this.value.trim().length > 0) {
                            btn.classList.remove('btn-outline-primary');
                            btn.classList.add('btn-success');
                            if (icon) {
                                icon.classList.remove('bi-chat-left-text');
                                icon.classList.add('bi-chat-left-check-fill');
                            }
                        } else {
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-primary');
                            if (icon) {
                                icon.classList.remove('bi-chat-left-check-fill');
                                icon.classList.add('bi-chat-left-text');
                            }
                        }
                    }
                }
            });
        });
    });
</script>
@endpush