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
        Schema::table('courses', function (Blueprint $table) {
            // Tahun Ajaran, contoh: "2025/2026"
            $table->string('academic_year', 20)->nullable()->after('name');
            // Semester: 1 atau 2
            $table->tinyInteger('semester')->unsigned()->nullable()->after('academic_year');
            // Kelas yang diampu, contoh: "10", "11", "12"
            $table->string('grade', 10)->nullable()->after('semester');
            // Relasi ke jurusan (majors)
            $table->foreignId('major_id')->nullable()->constrained('majors')->nullOnDelete()->after('grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['major_id']);
            $table->dropColumn(['academic_year', 'semester', 'grade', 'major_id']);
        });
    }
};
