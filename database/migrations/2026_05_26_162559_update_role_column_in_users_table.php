<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Sapu bersih dulu data yang nyangkut/rusak di tabel users dan jadikan 'student'
        DB::statement("UPDATE users SET role = 'student' WHERE role NOT IN ('admin', 'student', 'guru')");

        // 2. Ubah tipe data dari ENUM menjadi VARCHAR (String) agar ke depannya bebas masalah
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL DEFAULT 'student'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kosongkan saja karena kita ingin menetapkannya sebagai VARCHAR secara permanen
    }
};