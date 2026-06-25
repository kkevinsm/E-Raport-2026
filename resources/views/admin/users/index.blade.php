@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold text-dark">Manajemen Pengguna</h6>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">+ Tambah Pengguna</a>
    </div>
    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="guru-tab" data-bs-toggle="tab" data-bs-target="#tab-guru" type="button" role="tab" aria-selected="true">Daftar Guru</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="admin-tab" data-bs-toggle="tab" data-bs-target="#tab-admin" type="button" role="tab" aria-selected="false">Daftar Admin</button>
            </li>
        </ul>

        <div class="tab-content" id="userTabsContent">
            
            <div class="tab-pane fade show active" id="tab-guru" role="tabpanel" aria-labelledby="guru-tab">
                <div class="table-responsive">
                    <table id="guruTable" class="table table-hover w-100" style="border-top: 1px solid #dee2e6;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No</th>
                                <th>Nama Lengkap</th>
                                <th>NBM (Username)</th>
                                <th class="text-center" style="width: 240px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gurus as $index => $guru)
                            <tr>
                                <td class="ps-3 align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle"><strong>{{ $guru->name }}</strong></td>
                                <td class="align-middle">{{ $guru->username }}</td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.gurus.detail', $guru->id) }}" class="btn btn-sm btn-info text-white px-2">Detail</a>
                                        
                                        <form action="{{ route('admin.users.reset_password', $guru->id) }}" method="POST" onsubmit="return confirm('Reset password guru ini menjadi 123456?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning text-dark px-2">Reset Pass</button>
                                        </form>
                                        
                                        <form action="{{ route('admin.users.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger px-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-admin" role="tabpanel" aria-labelledby="admin-tab">
                <div class="table-responsive">
                    <table id="adminTable" class="table table-hover w-100" style="border-top: 1px solid #dee2e6;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th class="text-center" style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $index => $admin)
                            <tr>
                                <td class="ps-3 align-middle">{{ $index + 1 }}</td>
                                <td class="align-middle"><strong>{{ $admin->name }}</strong></td>
                                <td class="align-middle">{{ $admin->username }}</td>
                                <td class="align-middle text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <form action="{{ route('admin.users.reset_password', $admin->id) }}" method="POST" onsubmit="return confirm('Reset password admin ini menjadi 123456?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning text-dark px-2">Reset Pass</button>
                                        </form>
                                        
                                        <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger px-2">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tableConfig = {
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 10,
            "order": [[1, 'asc']],
            "dom": '<"d-flex justify-content-between align-items-center mb-3"l f>rt<"d-flex justify-content-between align-items-center mt-3"i p>',
        };

        if ( ! $.fn.DataTable.isDataTable( '#guruTable' ) ) {
            $('#guruTable').DataTable(tableConfig);
        }

        if ( ! $.fn.DataTable.isDataTable( '#adminTable' ) ) {
            $('#adminTable').DataTable(tableConfig);
        }
    });
</script>
@endpush