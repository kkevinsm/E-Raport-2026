<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('scores'); // Hapus yang lama jika ada

        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke tabel Kategori Nilai yang dinamis
            $table->foreignId('score_category_id')->constrained('score_categories')->cascadeOnDelete();
            
            // Menggunakan float/decimal agar nilai rata-rata bisa presisi (cth: 85.5)
            $table->float('score')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
