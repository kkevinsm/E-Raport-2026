<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Lewati baris jika NIS kosong (mencegah error pada baris kosong di Excel)
            if (!isset($row['nis'])) {
                continue;
            }

            // 1. Buat Akun User Terlebih Dahulu
            $user = User::create([
                'name'     => $row['nama'],
                'username' => $row['username'],
                'password' => Hash::make($row['password']),
                'role'     => 'student',
            ]);

            // 2. Buat Profil Siswa berdasarkan ID User yang baru dibuat
            Student::create([
                'user_id'     => $user->id,
                'nis'         => $row['nis'],
                'nisn'        => $row['nisn'] ?? null,
                'id_majors'   => $row['id_jurusan'], 
                'class_name'  => $row['kelas'], // Contoh: "10 TKJ 1"
                'school_year' => $row['tahun_masuk'] ?? null,
                'gender'      => $row['jenis_kelamin'] ?? null,
            ]);
        }
    }
}