@extends('layouts.app')

@section('content')
<div class="row">
    @foreach(['10', '11', '12'] as $class)
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold text-center">Kelas {{ $class }}</div>
            <div class="card-body">
                <canvas id="chart{{ $class }}"></canvas>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">Rata-rata Nilai per Mata Pelajaran</div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Rata-rata Seluruh Siswa</th>
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
@endsection`

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