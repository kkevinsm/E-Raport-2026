@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 text-dark">Tambah Mata Pelajaran</h5>
            <a href="{{ route('admin.courses') }}" class="btn btn-sm btn-secondary">&larr; Kembali</a>
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

                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Mata Pelajaran</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Matematika" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Guru Pengampuh</label>
                        <div class="card p-3 bg-light border-0">
                            @forelse($gurus as $guru)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="guru_ids[]" value="{{ $guru->id }}" id="guru{{ $guru->id }}">
                                    <label class="form-check-label" for="guru{{ $guru->id }}">
                                        {{ $guru->name }} ({{ $guru->username }})
                                    </label>
                                </div>
                            @empty
                                <small class="text-danger">Belum ada data Guru. Silakan tambahkan akun dengan role "Guru" di menu Users terlebih dahulu.</small>
                            @endforelse
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Simpan Mata Pelajaran</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection