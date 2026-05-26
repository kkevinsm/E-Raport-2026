<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom-kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
     */
    protected $fillable = [
        'name',
        'username', // Digunakan sebagai pengganti email/NBM saat login
        'password',
        'role',
        'nbm',
        'position',
        'gender',
        'date_of_birth',
        'address',
        'phone_number',
    ];

    /**
     * Kolom-kolom yang disembunyikan (tidak akan muncul saat data dipanggil via API/JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data dari kolom tertentu
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI ---

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}