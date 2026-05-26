<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::dropIfExists('classes');
        
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name_class', 50); // Contoh: "X TOI 1"
            
            // Relasi ke tabel majors (Jurusan)
            $table->foreignId('major_id')->constrained('majors')->cascadeOnDelete();
            
            // Relasi ke tabel users (Sesuai ERD, id_user ini biasanya untuk Wali Kelas)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};