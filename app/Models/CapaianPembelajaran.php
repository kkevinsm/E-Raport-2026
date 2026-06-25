<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianPembelajaran extends Model
{
    // Specify singular table name since it doesn't follow Laravel's default pluralization
    protected $table = 'capaian_pembelajaran';

    protected $fillable = [
        'student_id',
        'course_id',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
