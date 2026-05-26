@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">Edit Siswa: {{ $student->user->name }}</div>
    <div class="card-body">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ $student->user->name }}" required>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tingkat Kelas</label>
                    <select name="grade" class="form-select" required>
                        <option value="10" {{ $grade == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ $grade == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ $grade == '12' ? 'selected' : '' }}>12</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Jurusan</label>
                    <select name="id_majors" class="form-select" required>
                        @foreach($majors as $m)
                            <option value="{{ $m->id }}" {{ $student->id_majors == $m->id ? 'selected' : '' }}>{{ $m->name_major }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Nomor Kelas</label>
                    <input type="number" name="class_number" class="form-control" value="{{ $classNumber }}" required min="1">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.students') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection