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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
                                
                                <th class="bg-primary text-white align-middle" style="width: 120px;">Rata-rata</th>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Semua Nilai</button>
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

                // Tampilkan rata-rata (hanya hitung kolom yang sudah diisi)
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
    });
</script>
@endpush