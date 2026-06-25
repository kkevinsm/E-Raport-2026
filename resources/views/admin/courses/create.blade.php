@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <h5 class="mb-0 fw-bold text-dark">Tambah Mata Pelajaran</h5>
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

                    {{-- Baris 1: Tahun Ajaran & Semester --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="academic_year"
                                   class="form-control @error('academic_year') is-invalid @enderror"
                                   placeholder="Contoh: 2025/2026"
                                   value="{{ old('academic_year') }}"
                                   required>
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                <option value="" disabled {{ old('semester') ? '' : 'selected' }}>-- Pilih --</option>
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Nama Mata Pelajaran --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Contoh: Matematika"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Baris 3: Kelas & Jurusan --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Kelas <span class="text-danger">*</span></label>
                            <select name="grade" class="form-select @error('grade') is-invalid @enderror" required>
                                <option value="" disabled {{ old('grade') ? '' : 'selected' }}>-- Pilih --</option>
                                <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>Kelas 10</option>
                                <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>Kelas 11</option>
                                <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>Kelas 12</option>
                            </select>
                            @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Jurusan <span class="text-danger">*</span></label>
                            <select name="major_id" class="form-select @error('major_id') is-invalid @enderror" required>
                                <option value="" disabled {{ old('major_id') ? '' : 'selected' }}>-- Pilih Jurusan --</option>
                                @forelse($majors as $major)
                                    <option value="{{ $major->id }}" {{ old('major_id') == $major->id ? 'selected' : '' }}>
                                        {{ $major->name_major }}
                                    </option>
                                @empty
                                    <option disabled>Belum ada jurusan</option>
                                @endforelse
                            </select>
                            @error('major_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($majors->isEmpty())
                                <small class="text-danger d-block mt-1">Belum ada data Jurusan. Tambahkan terlebih dahulu di tab Master Data.</small>
                            @endif
                        </div>
                    </div>

                    {{-- Baris 4: Pilih Guru Pengampuh --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Guru Pengampuh <span class="text-danger">*</span></label>
                        @error('guru_ids')
                            <div class="alert alert-danger py-1 small mb-2">{{ $message }}</div>
                        @enderror
                        <div class="card p-3 bg-light border-0" style="max-height: 200px; overflow-y: auto;">
                            @forelse($gurus as $guru)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="guru_ids[]"
                                           value="{{ $guru->id }}"
                                           id="guru{{ $guru->id }}"
                                           {{ is_array(old('guru_ids')) && in_array($guru->id, old('guru_ids')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="guru{{ $guru->id }}">
                                        {{ $guru->name }}
                                        <span class="text-muted small">({{ $guru->username }})</span>
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