@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Profil Data Siswa</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%;">Nama Lengkap</th>
                            <td>: {{ $student->user->name }}</td>
                        </tr>
                        <tr>
                            <th>NIS (Username Login)</th>
                            <td>: {{ $student->nis }}</td>
                        </tr>
                        <tr>
                            <th>NISN</th>
                            <td>: {{ $student->nisn }}</td>
                        </tr>
                        <tr>
                            <th>Jurusan</th>
                            <td>: {{ $student->major->name_major ?? 'Belum diatur' }}</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>: <span class="badge bg-primary">{{ $student->class_name }}</span></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>: {{ $student->gender ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>: {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Orang Tua</th>
                            <td>: {{ $student->name_parent ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>: {{ $student->phone_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Lengkap</th>
                            <td>: {{ $student->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection