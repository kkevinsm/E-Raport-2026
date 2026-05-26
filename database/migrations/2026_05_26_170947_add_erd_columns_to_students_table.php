<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('school_year')->nullable()->after('user_id');
            $table->integer('id_majors')->nullable()->after('nis'); // Sesuai ERD untuk relasi jurusan
            $table->string('gender', 50)->nullable()->after('id_majors');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('name_parent', 50)->nullable()->after('date_of_birth');
            $table->string('address', 300)->nullable()->after('name_parent');
            $table->string('phone_number', 15)->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['school_year', 'id_majors', 'gender', 'date_of_birth', 'name_parent', 'address', 'phone_number']);
        });
    }
};