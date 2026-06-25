<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'academic_year', 'semester', 'grade', 'major_id'];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function capaianPembelajaran()
    {
        return $this->hasMany(CapaianPembelajaran::class, 'course_id');
    }
}