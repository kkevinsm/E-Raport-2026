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
            if (!isset($row['nis']) || empty(trim($row['nis']))) {
                continue;
            }

            // 1. Buat Akun User Terlebih Dahulu
            $user = User::create([
                'name'     => $row['nama'],
                'username' => $row['username'],
                'password' => Hash::make($row['password']),
                'role'     => 'student',
            ]);

            // 2. Konversi Jenis Kelamin ke format in:Laki-laki,Perempuan
            $gender = null;
            $jk = strtolower(trim($row['jenis_kelamin'] ?? ''));
            if ($jk === 'l' || $jk === 'laki-laki' || $jk === 'laki laki') {
                $gender = 'Laki-laki';
            } elseif ($jk === 'p' || $jk === 'perempuan') {
                $gender = 'Perempuan';
            }

            // 3. Bangun string nama kelas (e.g. "10 Teknik Komputer dan Jaringan 1")
            $major = \App\Models\Major::find($row['id_jurusan']);
            $className = trim(($row['tingkat_kelas'] ?? '') . ' ' . ($major ? $major->name_major : '') . ' ' . ($row['nomor_kelas'] ?? ''));

            // 4. Buat Profil Siswa
            Student::create([
                'user_id'       => $user->id,
                'nis'           => $row['nis'],
                'nisn'          => $row['nisn'] ?? null,
                'class_name'    => $className,
                'id_majors'     => $row['id_jurusan'],
                'school_year'   => $row['tahun_masuk'] ?? null,
                'gender'        => $gender,
                'date_of_birth' => $row['tanggal_lahir'] ?? null,
                'name_parent'   => $row['nama_orang_tua'] ?? null,
                'phone_number'  => $row['no_telepon'] ?? null,
                'address'       => $row['alamat'] ?? null,
            ]);
        }
    }
}