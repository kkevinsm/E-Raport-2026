<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MajorSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel terlebih dahulu agar tidak ada duplikat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('majors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Masukkan 4 Jurusan Baku secara berurutan
        DB::table('majors')->insert([
            ['id' => 1, 'name_major' => 'Teknik Kendaraan Ringan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name_major' => 'Teknik Pemesinan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name_major' => 'Teknik Instalasi Tenaga Listrik', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name_major' => 'Desain Komunikasi Visual', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}