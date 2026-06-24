@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <h5 class="mb-0 fw-bold text-dark">Tambah User Baru</h5>
            <a href="{{ route('admin.users') }}" class="btn btn-sm btn-secondary">&larr; Kembali</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required value="{{ old('name') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">NBM (Username)</label>
                        <input type="text" name="username" class="form-control" placeholder="Username untuk login" required value="{{ old('username') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hak Akses (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin (Akses Penuh)</option>
                            <option value="guru">Guru (Hanya Input Nilai)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary w-100 py-2">Simpan Data User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection