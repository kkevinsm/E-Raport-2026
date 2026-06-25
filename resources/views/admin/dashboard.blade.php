@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Left Column: Charts and Table -->
    <div class="col-lg-8">
        <div class="row">
            @foreach(['10', '11', '12'] as $class)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white fw-bold text-center">Kelas {{ $class }}</div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="chart{{ $class }}"></canvas>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Nilai Akhir per Mata Pelajaran</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Nilai Akhir Seluruh Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($averageScores as $course)
                            <tr>
                                <td>{{ $course->name }}</td>
                                <td><span class="badge bg-primary">{{ number_format($course->scores_avg_score, 1) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Profile and Date Cards -->
    <div class="col-lg-4">
        <!-- Card Profile (myITS Style) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 54px; height: 54px; background: linear-gradient(135deg, #1b68cf 0%, #8b5cf6 100%);">
                        <i class="bi bi-person-fill fs-2"></i>
                    </div>
                    <div class="overflow-hidden">
                        <h5 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 1.15rem;">{{ Auth::user()->name }}</h5>
                    </div>
                </div>
                <div class="ps-1">
                    <button class="btn btn-link text-decoration-none fw-semibold d-inline-flex align-items-center gap-2 mt-1 p-0 border-0" style="color: #1b68cf; font-size: 0.95rem;" data-bs-toggle="modal" data-bs-target="#accountSettingsModal">
                        <span>Account Settings</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Card Tanggal (myITS Style) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-1" style="color: #0b2545; font-size: 1.75rem;">{{ now()->translatedFormat('l') }}</h3>
                <p class="fw-semibold mb-0" style="color: #1b68cf; font-size: 1.05rem;">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="chart-data" type="application/json">
    @json($chartData)
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartData = JSON.parse(document.getElementById('chart-data').textContent);
        
        // Ubah juga array di JavaScript ini menjadi 10, 11, 12
        ['10', '11', '12'].forEach(cls => {
            const data = chartData[cls];
            
            // Cek apakah ada data siswa untuk kelas tersebut
            if(data && data.length > 0) {
                new Chart(document.getElementById('chart' + cls), {
                    type: 'pie',
                    data: {
                        labels: data.map(d => d.name_major),
                        datasets: [{
                            data: data.map(d => d.total),
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796']
                        }]
                    }
                });
            } else {
                // Jika kosong, sembunyikan canvas dan tampilkan teks alternatif
                let canvas = document.getElementById('chart' + cls);
                canvas.style.display = 'none';
                canvas.parentElement.innerHTML = '<p class="text-muted text-center my-4">Belum ada data siswa</p>';
            }
        });
    });
</script>
@endpush