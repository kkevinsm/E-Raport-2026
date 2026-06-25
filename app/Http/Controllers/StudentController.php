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
    public function dashboard(Request $request)
    {
        $student = Student::where('user_id', Auth::id())
                    ->with(['user', 'major'])
                    ->firstOrFail();

        // Ambil semua ID mata pelajaran yang diikuti siswa atau yang sudah dinilai
        $enrolledCourseIds = $student->courses()->pluck('courses.id')->toArray();
        $scoredCourseIds = \App\Models\Score::where('student_id', $student->id)->pluck('course_id')->toArray();
        $courseIds = array_unique(array_merge($enrolledCourseIds, $scoredCourseIds));

        // Ambil semua pasangan Tahun Ajaran & Semester unik dari mata pelajaran tersebut
        $periods = Course::whereIn('id', $courseIds)
            ->select('academic_year', 'semester')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        $selectedYear = $request->input('academic_year');
        $selectedSemester = $request->input('semester');

        // Jika filter belum diset, default ke tahun ajaran & semester terbaru yang tersedia
        if (!$selectedYear && !$selectedSemester && $periods->isNotEmpty()) {
            $selectedYear = $periods->first()->academic_year;
            $selectedSemester = $periods->first()->semester;
        }

        // Ambil list mata pelajaran yang sesuai periode terpilih
        $courses = collect();
        if ($selectedYear && $selectedSemester) {
            $courses = Course::where('academic_year', $selectedYear)
                ->where('semester', $selectedSemester)
                ->whereIn('id', $courseIds)
                ->get();
        }

        $categories = ScoreCategory::all();

        // Load nilai siswa hanya untuk mata pelajaran pada periode terpilih
        $student->load([
            'scores' => function($q) use ($courses) {
                if ($courses->isNotEmpty()) {
                    $q->whereIn('course_id', $courses->pluck('id'));
                } else {
                    $q->whereRaw('1 = 0');
                }
            },
            'scores.category',
            'scores.course',
            'capaianPembelajaran' => function($q) use ($courses) {
                if ($courses->isNotEmpty()) {
                    $q->whereIn('course_id', $courses->pluck('id'));
                } else {
                    $q->whereRaw('1 = 0');
                }
            }
        ]);

        return view('student.dashboard', compact(
            'student', 
            'periods', 
            'selectedYear', 
            'selectedSemester', 
            'courses', 
            'categories'
        ));
    }

    public function profile()
    {
        // Menambahkan with('user') agar data nama, email, dll bisa diakses
        $student = Student::where('user_id', Auth::id())
                    ->with('user') 
                    ->firstOrFail();
        
        return view('student.profile', compact('student'));
    }

    public function exportPdf(Request $request, Student $student)
    {
        // Security check: Jika role student, hanya boleh download rapor sendiri
        if (Auth::user()->role === 'student') {
            $currentStudent = Student::where('user_id', Auth::id())->first();
            if (!$currentStudent || $currentStudent->id !== $student->id) {
                abort(403, 'Anda tidak diizinkan mengakses data siswa lain.');
            }
        }

        // Validasi parameter wajib
        $request->validate([
            'academic_year' => 'required|string',
            'semester'      => 'required|in:1,2',
        ]);

        $academicYear = $request->academic_year;
        $semester     = (int) $request->semester;

        $categories = ScoreCategory::all();
        
        // Ambil HANYA mapel yang sesuai tahun ajaran & semester yang dipilih
        // DAN memiliki nilai untuk siswa ini
        $courses = Course::where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->whereHas('scores', function($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->get();

        // Load semua scores siswa ini untuk mapel yang terfilter
        $student->load([
            'user',
            'scores' => function($q) use ($courses) {
                $q->whereIn('course_id', $courses->pluck('id'));
            },
            'scores.category',
            'capaianPembelajaran' => function($q) use ($courses) {
                $q->whereIn('course_id', $courses->pluck('id'));
            }
        ]);

        $semesterLabel = $semester == 1 ? 'Semester 1 (Ganjil)' : 'Semester 2 (Genap)';

        $data = [
            'student'       => $student,
            'courses'       => $courses,
            'categories'    => $categories,
            'academic_year' => $academicYear,
            'semester'      => $semester,
            'semester_label'=> $semesterLabel,
            'date'          => date('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.rapor', $data);
        $filename = 'Rapor_' . $student->user->name . '_' . str_replace('/', '-', $academicYear) . '_Smt' . $semester . '.pdf';
        return $pdf->download($filename);
    }

    public function exportPdfStudent(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        return $this->exportPdf($request, $student);
    }

    public function exportPdfBulk(Request $request)
    {
        // Security check: Only admins are allowed to do bulk exports
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak diizinkan melakukan cetak massal.');
        }

        $request->validate([
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'academic_year' => 'required|string',
            'semester'      => 'required|in:1,2',
        ]);

        $studentIds = $request->student_ids;
        $academicYear = $request->academic_year;
        $semester = (int) $request->semester;

        $categories = ScoreCategory::all();

        // Ambil data untuk masing-masing siswa yang dipilih
        $studentsData = [];
        foreach ($studentIds as $id) {
            $student = Student::with([
                'user',
                'scores' => function($q) use ($academicYear, $semester) {
                    $q->whereHas('course', function($c) use ($academicYear, $semester) {
                        $c->where('academic_year', $academicYear)->where('semester', $semester);
                    })->with('category');
                },
                'capaianPembelajaran' => function($q) use ($academicYear, $semester) {
                    $q->whereHas('course', function($c) use ($academicYear, $semester) {
                        $c->where('academic_year', $academicYear)->where('semester', $semester);
                    });
                }
            ])->find($id);

            // Cari mapel yang sesuai tahun ajaran & semester yang dipilih
            // DAN memiliki nilai untuk siswa ini
            $courses = Course::where('academic_year', $academicYear)
                ->where('semester', $semester)
                ->whereHas('scores', function($q) use ($student) {
                    $q->where('student_id', $student->id);
                })
                ->get();

            if ($courses->isNotEmpty()) {
                $studentsData[] = [
                    'student'        => $student,
                    'courses'        => $courses,
                    'academic_year'  => $academicYear,
                    'semester_label' => $semester == 1 ? 'Semester 1 (Ganjil)' : 'Semester 2 (Genap)'
                ];
            }
        }

        if (empty($studentsData)) {
            return back()->withErrors('Tidak ada data nilai/rapor yang dapat dicetak untuk siswa dan periode terpilih.');
        }

        $data = [
            'studentsData' => $studentsData,
            'categories'   => $categories,
            'date'         => date('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.rapor_bulk', $data);
        $filename = 'Rapor_Massal_' . str_replace('/', '-', $academicYear) . '_Smt' . $semester . '.pdf';
        return $pdf->download($filename);
    }
}