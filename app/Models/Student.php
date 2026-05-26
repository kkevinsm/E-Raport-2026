<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'class_name',
        'school_year',
        'id_majors',
        'gender',
        'date_of_birth',
        'name_parent',
        'address',
        'phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'id_majors');
    }
    
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student');
    }

    }