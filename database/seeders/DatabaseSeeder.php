<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Major;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin Default
        User::create([
            'name' => 'Admin System',
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // 2. Tambahkan Data Jurusan Default (Agar form Tambah Siswa bisa langsung dipakai)
        Major::create(['name_major' => 'Teknik Komputer dan Jaringan']);
    }
}