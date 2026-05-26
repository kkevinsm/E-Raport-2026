<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom sesuai ERD (nullable agar data lama tidak error)
            $table->string('nbm')->nullable()->after('name'); // ERD menggunakan int, tapi string lebih aman untuk NBM
            $table->string('position', 50)->nullable()->after('role');
            $table->string('gender', 50)->nullable()->after('position');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('address', 300)->nullable()->after('date_of_birth');
            $table->string('phone_number', 15)->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nbm', 'position', 'gender', 'date_of_birth', 'address', 'phone_number']);
        });
    }
};