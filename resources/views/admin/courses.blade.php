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

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-dark">Manajemen Jurusan</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.majors.store') }}" method="POST" class="row g-2 mb-3">
            @csrf
            <div class="col-md-10">
                <input type="text" name="name_major" class="form-control" placeholder="Contoh: Teknik Otomasi Industri" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Tambah</button>
            </div>
        </form>

        <div class="d-flex flex-wrap gap-2">
            @forelse(\App\Models\Major::all() as $major)
                <span class="badge bg-info p-2 d-flex align-items-center shadow-sm">
                    {{ $major->name_major }}
                    <form action="{{ route('admin.majors.destroy', $major->id) }}" method="POST" class="ms-2">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-close btn-close-white" style="font-size: 0.5rem;" onclick="return confirm('Hapus jurusan ini?')"></button>
                    </form>
                </span>
            @empty
                <span class="text-muted small">Belum ada jurusan.</span>
            @endforelse
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
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
                        <th class="ps-3" style="width: 80px;">No</th>
                        <th>Nama Mata Pelajaran</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $index => $course)
                    <tr>
                        <td class="ps-3 align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle"><strong>{{ $course->name }}</strong></td>
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
                "order": [[1, 'asc']],
                "dom": '<"d-flex justify-content-between align-items-center mb-3"l f>rt<"d-flex justify-content-between align-items-center mt-3"i p>',
            });
        }
    });
</script>
@endpush