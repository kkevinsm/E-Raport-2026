<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    // Karena nama model ClassRoom, kita paksa Laravel agar membaca tabel 'classes'
    protected $table = 'classes';

    protected $fillable = [
        'name_class',
        'major_id',
        'user_id', // Untuk Wali Kelas
    ];

    // Relasi: Kelas ini milik jurusan apa?
    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    // Relasi: Kelas ini diampu oleh Wali Kelas (User) siapa?
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}