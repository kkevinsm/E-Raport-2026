@extends('layouts.app')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-dark">Kelola Kategori Nilai (Kolom Nilai)</h6>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Kategori ini akan muncul sebagai kolom di tabel input nilai guru.</p>
        <form action="{{ route('admin.score_categories.store') }}" method="POST" class="row g-2 mb-3">
            @csrf
            <div class="col-md-10">
                <input type="text" name="name" class="form-control" placeholder="Contoh: Tugas, UTS, UAS, Proyek" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Tambah</button>
            </div>
        </form>

        <div class="d-flex flex-wrap gap-2">
            @forelse(\App\Models\ScoreCategory::all() as $cat)
                <span class="badge bg-primary p-2 d-flex align-items-center shadow-sm">
                    {{ $cat->name }}
                    <form action="{{ route('admin.score_categories.destroy', $cat->id) }}" method="POST" class="ms-2">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-close btn-close-white" style="font-size: 0.5rem;" onclick="return confirm('Hapus kategori ini?')"></button>
                    </form>
                </span>
            @empty
                <span class="text-muted small">Belum ada kategori nilai.</span>
            @endforelse
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 py-3">
        <h6 class="mb-0 fw-bold text-dark">Daftar Mata Pelajaran</h6>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-sm btn-primary">+ Tambah Mapel</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="table-responsive">
            <table id="courseTable" class="table table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Guru Pengampuh</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $index => $course)
                    <tr>
                        <td class="ps-3 align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle">
                            {{ $course->academic_year ?? '-' }}
                        </td>
                        <td class="align-middle">
                            @if($course->semester == 1)
                                <span class="badge bg-info text-dark">Semester 1</span>
                            @elseif($course->semester == 2)
                                <span class="badge bg-warning text-dark">Semester 2</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="align-middle"><strong>{{ $course->name }}</strong></td>
                        <td class="align-middle">
                            @if($course->grade)
                                <span class="badge bg-secondary">Kelas {{ $course->grade }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            {{ $course->major->name_major ?? '-' }}
                        </td>
                        <td class="align-middle">
                            @forelse($course->teachers as $guru)
                                <span class="badge bg-light text-dark border me-1">{{ $guru->name }}</span>
                            @empty
                                <span class="text-muted small">Belum ada</span>
                            @endforelse
                        </td>
                        <td class="align-middle text-center">
                            <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mapel ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger px-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ( ! $.fn.DataTable.isDataTable( '#courseTable' ) ) {
            $('#courseTable').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
                "pageLength": 10,
                "order": [[1, 'asc'], [2, 'asc']],
                "dom": '<"d-flex justify-content-between align-items-center mb-3"l f>rt<"d-flex justify-content-between align-items-center mt-3"i p>',
            });
        }
    });
</script>
@endpush