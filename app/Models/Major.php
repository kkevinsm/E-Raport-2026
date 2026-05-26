<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $fillable = ['name_major'];

    // Relasi: Satu jurusan memiliki banyak kelas
    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'major_id');
    }

    // Relasi: Satu jurusan memiliki banyak siswa
    public function students()
    {
        return $this->hasMany(Student::class, 'id_majors'); // Menggunakan nama kolom yang sudah kita buat sebelumnya
    }
}