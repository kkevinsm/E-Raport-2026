<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Akses tidak diizinkan');
        }

        $guru = User::where('id', Auth::id())->with('courses')->first();
        return view('guru.dashboard', compact('guru'));
    }

    // --- FITUR INPUT NILAI ---

    public function inputNilai(Course $course)
    {
        if (!Auth::user()->courses->contains($course->id)) {
            abort(403, 'Anda tidak mengampuh mata pelajaran ini.');
        }

        // Ambil SEMUA kategori nilai yang dibuat oleh Admin
        $categories = \App\Models\ScoreCategory::all();

        // PERBAIKAN DI SINI:
        // Mengambil HANYA siswa yang berelasi dengan mapel ini melalui tabel pivot (course_student)
        $students = $course->students()->with(['user', 'scores' => function($query) use ($course) {
            $query->where('course_id', $course->id);
        }])->get();

        return view('guru.input_nilai', compact('course', 'students', 'categories'));
    }

    public function saveNilai(Request $request, Course $course)
    {
        if (!Auth::user()->courses->contains($course->id)) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'scores' => 'required|array',
            'scores.*.*' => 'nullable|numeric|min:0|max:100', 
        ]);

        foreach ($request->scores as $student_id => $category_scores) {
            foreach ($category_scores as $category_id => $score_value) {
                if ($score_value !== null) {
                    Score::updateOrCreate(
                        [
                            'student_id' => $student_id, 
                            'course_id' => $course->id, 
                            'score_category_id' => $category_id
                        ],
                        ['score' => $score_value]
                    );
                }
            }
        }

        return back()->with('success', 'Semua Kategori Nilai berhasil disimpan!');
    }
}