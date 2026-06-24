@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <h5 class="mb-0 fw-bold text-dark">Tambah Siswa Baru</h5>
            <a href="{{ route('admin.students') }}" class="btn btn-sm btn-secondary">&larr; Kembali</a>
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

                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">A. Informasi Akademik</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIS <span class="text-danger">*</span></label>
                            <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Siswa" required value="{{ old('nis') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NISN</label>
                            <input type="text" name="nisn" class="form-control" placeholder="Nomor Induk Siswa Nasional" value="{{ old('nisn') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tingkat Kelas <span class="text-danger">*</span></label>
                            <select name="grade" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>10</option>
                                <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>11</option>
                                <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>12</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jurusan <span class="text-danger">*</span></label>
                            <select name="id_majors" class="form-select" required>
                                <option value="">-- Pilih Jurusan --</option>
                                @forelse($majors as $major)
                                    <option value="{{ $major->id }}" {{ old('id_majors') == $major->id ? 'selected' : '' }}>{{ $major->name_major }}</option>
                                @empty
                                    <option value="" disabled>Belum ada data jurusan. Tambahkan di Database.</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Nomor Kelas <span class="text-danger">*</span></label>
                            <input type="number" name="class_number" class="form-control" placeholder="Cth: 1" required min="1" value="{{ old('class_number') }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tahun Masuk</label>
                            <input type="number" name="school_year" class="form-control" placeholder="Cth: 2026" value="{{ old('school_year') }}">
                        </div>
                    </div>
                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">B. Biodata Pribadi</h6>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama lengkap siswa sesuai ijazah" required value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Orang Tua / Wali</label>
                            <input type="text" name="name_parent" class="form-control" placeholder="Nama ayah/ibu/wali" value="{{ old('name_parent') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nomor Telepon (WA)</label>
                            <input type="text" name="phone_number" class="form-control" placeholder="Cth: 08123456789" value="{{ old('phone_number') }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap siswa">{{ old('address') }}</textarea>
                    </div>

                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">C. Kredensial Login</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Username Login <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" placeholder="Disarankan menggunakan NIS" required value="{{ old('username') }}">
                            <small class="text-muted">Digunakan siswa untuk login ke E-Raport.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Simpan Data Siswa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection