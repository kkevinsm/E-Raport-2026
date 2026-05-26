@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold">Daftar Siswa</h6>
        
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                Import Data Siswa
            </button>
            <a href="{{ route('admin.students.create') }}" class="btn btn-sm btn-primary">+ Tambah Siswa</a>
        </div>
        
    </div>
    <div class="card-body"> 
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <table id="studentTable" class="table table-hover w-100">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">NIS / NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jurusan</th> 
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="ps-3">
                        <strong>{{ $student->nis }}</strong><br>
                        <small class="text-muted">{{ $student->nisn }}</small>
                    </td>
                    <td class="align-middle"><strong>{{ $student->user->name }}</strong></td>
                    <td class="align-middle">{{ $student->class_name }}</td>
                    <td class="align-middle">{{ $student->major->name_major ?? '-' }}</td>
                    
                    <td class="align-middle text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            
                            <a href="{{ route('admin.students.manage_courses', $student->id) }}" class="btn btn-sm btn-info text-white">Atur Mapel</a>
                            
                            <a href="{{ route('admin.students.scores', $student->id) }}" class="btn btn-sm btn-warning text-dark">Nilai</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih File (.xlsx / .csv)</label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx,.csv,.xls" required>
                    </div>
                    <div class="alert alert-info py-2">
                        <small>Pastikan format kolom (header) pada baris pertama Excel Anda sesuai dengan template sistem.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#studentTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 10,
            "order": [[1, 'asc']] // Tetap urutkan berdasarkan Nama Siswa
        });
    });
</script>
@endpush