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
        // 1. Buat Akun Admin Default (hanya jika belum ada)
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'name' => 'Admin System',
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]);
        }

        // 2. Tambahkan Data Jurusan Default (hanya jika belum ada)
        $majors = [
            'Teknik Kendaraan Ringan',
            'Desain Komunikasi Visual',
            'Teknik Instalasi Tenaga Listrik',
            'Teknik Pemesinan'
        ];

        foreach ($majors as $majorName) {
            Major::firstOrCreate(['name_major' => $majorName]);
        }

    }
}