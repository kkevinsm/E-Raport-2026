<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\ScoreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function dashboard()
    {
        // Menggunakan with() agar query lebih efisien dan relasi tersedia
        $student = Student::where('user_id', Auth::id())
                    ->with(['scores.course', 'scores.category'])
                    ->firstOrFail(); // Gunakan firstOrFail() agar tidak error jika data tidak ditemukan
        
        return view('student.dashboard', compact('student'));
    }

    public function profile()
    {
        // Menambahkan with('user') agar data nama, email, dll bisa diakses
        $student = Student::where('user_id', Auth::id())
                    ->with('user') 
                    ->firstOrFail();
        
        return view('student.profile', compact('student'));
    }

    public function exportPdf(Student $student)
    {
        // Opsional: Jika ingin membatasi agar siswa tidak bisa export rapor orang lain
        // if (Auth::user()->role == 'student' && $student->user_id !== Auth::id()) {
        //     abort(403);
        // }

        $categories = ScoreCategory::all();
        
        // Mengambil mapel yang memiliki data nilai untuk siswa ini
        $courses = Course::whereHas('scores', function($q) use ($student) {
            $q->where('student_id', $student->id);
        })->get();

        $data = [
            'student' => $student->load('user'), // Memastikan relasi user dimuat
            'courses' => $courses,
            'categories' => $categories,
            'date' => date('d F Y')
        ];

        $pdf = Pdf::loadView('pdf.rapor', $data);
        return $pdf->download('Rapor_' . $student->user->name . '.pdf');
    }
}