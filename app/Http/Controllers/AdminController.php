<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Score;
use App\Models\Major;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Mengambil data untuk Pie Chart menggunakan angka 10, 11, 12
        $classes = ['10', '11', '12'];
        $chartData = [];

        foreach ($classes as $class) {
            // Menambahkan spasi setelah $class agar sistem mencari string dengan format "10 [Spasi] Nama Jurusan"
            $chartData[$class] = \App\Models\Student::where('class_name', 'like', "$class %")
                ->join('majors', 'students.id_majors', '=', 'majors.id')
                ->select('majors.name_major', DB::raw('count(*) as total'))
                ->groupBy('majors.name_major')
                ->get();
        }

        // Mengambil data nilai akhir per mapel untuk tabel
        $averageScores = \App\Models\Course::withAvg('scores', 'score')->get();

        return view('admin.dashboard', compact('chartData', 'averageScores'));
    }

    // ==========================================
    // --- FITUR TAB 2: USERS (GURU/ADMIN) ---
    // ==========================================

    public function indexUsers()
    {
        // Pisahkan data Admin dan Guru
        $admins = User::where('role', 'admin')->get();
        $gurus = User::where('role', 'guru')->get();
        
        // Kirim kedua variabel tersebut ke view
        return view('admin.users.index', compact('admins', 'gurus'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users', 
            'password' => 'required|min:6',
            'role' => 'required|in:admin,guru',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'Data berhasil ditambahkan!');
    }

    public function destroyUser(User $user)
    {
        if (Auth::id() == $user->getKey()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }
        
        $user->delete();
        return back()->with('success', 'Data Guru/Admin berhasil dihapus!');
    }

    // ==========================================
    // --- FITUR TAB 4: COURSES (MATA PELAJARAN) ---
    // ==========================================

    public function indexCourses()
    {
        $courses = Course::with(['teachers', 'major'])->get();
        return view('admin.courses', compact('courses'));
    }

    public function createCourse()
    {
        $gurus  = User::where('role', 'guru')->get();
        $majors = Major::all();
        return view('admin.courses.create', compact('gurus', 'majors'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'academic_year' => 'required|string|max:20',
            'semester'      => 'required|in:1,2',
            'grade'         => 'required|in:10,11,12',
            'major_id'      => 'required|exists:majors,id',
            'guru_ids'      => 'required|array',
            'guru_ids.*'    => 'exists:users,id',
        ]);

        $course = Course::create([
            'name'          => $request->name,
            'academic_year' => $request->academic_year,
            'semester'      => $request->semester,
            'grade'         => $request->grade,
            'major_id'      => $request->major_id,
        ]);

        $course->teachers()->attach($request->guru_ids);

        return redirect()->route('admin.courses')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
    }

    public function destroyCourse(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Mata Pelajaran berhasil dihapus!');
    }

    // ==========================================
    // --- FITUR TAB 3: STUDENTS (SISWA) ---
    // ==========================================

    public function indexStudents()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user->role == 'admin') {
            // 1. Jika Admin: Tampilkan SEMUA siswa
            $students = Student::with(['user', 'major'])->get();
        } else {
            // 2. Jika Guru: Tampilkan HANYA siswa yang mengambil mapel yang diajarkan guru ini
            $guruCourseIds = $user->courses->pluck('id');
            
            $students = Student::whereHas('courses', function ($query) use ($guruCourseIds) {
                $query->whereIn('courses.id', $guruCourseIds);
            })->with(['user', 'major'])->get();
        }

        // Ambil daftar Tahun Ajaran dari tabel courses untuk modal cetak rapor massal
        $academicYears = \App\Models\Course::select('academic_year')->distinct()->pluck('academic_year');
        
        return view('admin.students.index', compact('students', 'academicYears'));
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,csv,xls|max:2048'
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file_excel'));
            return back()->with('success', 'Ratusan data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return back()->withErrors('Gagal mengimport data. Pastikan format Excel sudah benar. Error: ' . $e->getMessage());
        }
    }

    public function manageStudentCourses(Student $student)
    {
        $allCourses = Course::with('major')->get();
        // Mengambil ID mapel yang sudah diambil siswa ini
        $assignedCourseIds = $student->courses->pluck('id')->toArray(); 
        
        return view('admin.students.manage_courses', compact('student', 'allCourses', 'assignedCourseIds'));
    }

    // Menyimpan perubahan mapel siswa
    public function updateStudentCourses(Request $request, Student $student)
    {
        // Sync akan otomatis menambah/menghapus relasi di database sesuai checkbox yang dicentang
        $student->courses()->sync($request->course_ids ?? []);
        
        return back()->with('success', 'Mata pelajaran untuk siswa ini berhasil diperbarui!');
    }

    public function createStudent()
    {
        $majors = Major::all();
        return view('admin.students.create', compact('majors'));
    }

    public function storeStudent(Request $request)
    {
        // 1. Validasi data yang masuk dari form (Pecah logika kelas)
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users', 
            'password' => 'required|min:6',
            'nis' => 'required|unique:students',
            'nisn' => 'nullable|string',
            'grade' => 'required', // Tambahan input dropdown kelas (10, 11, 12)
            'id_majors' => 'required|exists:majors,id', 
            'class_number' => 'required|numeric', // Tambahan input nomor kelas (1, 2, dll)
            'school_year' => 'nullable|integer',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'date_of_birth' => 'nullable|date',
            'name_parent' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:300',
        ]);

        // 2. Gabungkan Kelas, Jurusan, dan Nomor Kelas menjadi 1 string
        $major = Major::find($request->id_majors);
        $className = $request->grade . ' ' . $major->name_major . ' ' . $request->class_number;

        // 3. Buat akun login (User)
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        // 4. Simpan biodata siswa
        Student::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'class_name' => $className, // Menggunakan variabel gabungan
            'id_majors' => $request->id_majors,
            'school_year' => $request->school_year,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'name_parent' => $request->name_parent,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.students')->with('success', 'Data Siswa lengkap berhasil ditambahkan!');
    }

    public function editStudent(Student $student)
    {
        $majors = Major::all();
        
        // Memecah kembali class_name menjadi array untuk kebutuhan form edit
        // Asumsi format "10 Teknik Kelistrikan dan Jaringan 1"
        // Kata pertama = grade, kata terakhir = class_number
        $classParts = explode(' ', $student->class_name);
        $grade = $classParts[0];
        $classNumber = end($classParts);

        return view('admin.students.edit', compact('student', 'majors', 'grade', 'classNumber'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required',
            'grade' => 'required', // 10, 11, 12
            'id_majors' => 'required|exists:majors,id',
            'class_number' => 'required|numeric', // 1, 2, dll
        ]);

        // Gabungkan kembali
        $major = Major::find($request->id_majors);
        $className = $request->grade . ' ' . $major->name_major . ' ' . $request->class_number;

        // Update nama di tabel users
        $student->user->update(['name' => $request->name]);

        // Update data di tabel students
        $student->update([
            'class_name' => $className,
            'id_majors' => $request->id_majors,
        ]);

        return redirect()->route('admin.students')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function showStudentScores(Student $student)
    {
        $student->load('user', 'scores.course', 'scores.category');
        
        if (\Illuminate\Support\Facades\Auth::user()->role == 'admin') {
            // Jika Admin: Tampilkan semua mapel yang sudah DIBERIKAN kepada siswa tersebut
            $courses = $student->courses;
        } else {
            // Jika Guru: Tampilkan HANYA mapel yang diajarkan guru ini DAN yang diambil siswa ini
            $guruCourses = \Illuminate\Support\Facades\Auth::user()->courses;
            $courses = $student->courses->intersect($guruCourses);
        }

        // Ambil daftar tahun ajaran yang unik dari courses siswa (untuk dropdown export PDF)
        $availableYears = $student->courses
            ->pluck('academic_year')
            ->filter()
            ->unique()
            ->values();

        return view('admin.students.scores', compact('student', 'courses', 'availableYears'));
    }

    public function storeStudentScore(Request $request, Student $student)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'score_category_id' => 'required|exists:score_categories,id', 
            'score' => 'required|numeric|min:0|max:100',
        ]);

        Score::updateOrCreate(
            [
                'student_id' => $student->getKey(), 
                'course_id' => $request->course_id,
                'score_category_id' => $request->score_category_id
            ],
            ['score' => $request->score]
        );

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    public function destroyStudent(Student $student)
    {
        $student->user->delete();
        return back()->with('success', 'Data Siswa berhasil dihapus!');
    }

    // ==========================================
    // --- FITUR RESET PASSWORD ---
    // ==========================================

    public function resetUserPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('123456') 
        ]);

        return back()->with('success', 'Password milik ' . $user->name . ' berhasil direset menjadi: 123456');
    }

    public function resetStudentPassword(Student $student)
    {
        $student->user->update([
            'password' => Hash::make('123456') 
        ]);

        return back()->with('success', 'Password milik siswa ' . $student->user->name . ' berhasil direset menjadi: 123456');
    }

    // ==========================================
    // --- FITUR DETAIL & PENUGASAN MAPEL GURU ---
    // ==========================================

    public function showGuruDetail(User $user)
    {
        if ($user->role !== 'guru') {
            return redirect()->route('admin.users')->with('error', 'User tersebut bukan guru.');
        }

        $user->load('courses.major');
        
        $assignedCourseIds = $user->courses->pluck('id');
        $availableCourses = Course::with('major')->whereNotIn('id', $assignedCourseIds)->get();

        return view('admin.users.guru_detail', compact('user', 'availableCourses'));
    }

    public function assignCourseToGuru(Request $request, User $user)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $user->courses()->syncWithoutDetaching([$request->course_id]);

        return back()->with('success', 'Mata Pelajaran berhasil ditugaskan kepada Guru ini.');
    }

    public function removeCourseFromGuru(User $user, Course $course)
    {
        $user->courses()->detach($course->id);
        return back()->with('success', 'Mata Pelajaran berhasil dilepas.');
    }

    // ==========================================
    // --- FITUR DATA MASTER (KATEGORI & JURUSAN) ---
    // ==========================================

    public function storeScoreCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        \App\Models\ScoreCategory::create(['name' => $request->name]);
        return back()->with('success', 'Kategori nilai berhasil ditambah.');
    }

    public function destroyScoreCategory(int $id)
    {
        \App\Models\ScoreCategory::findOrFail($id)->delete();
        return back()->with('success', 'Kategori nilai berhasil dihapus.');
    }

    public function storeMajor(Request $request)
    {
        $request->validate(['name_major' => 'required|string|max:150']);
        \App\Models\Major::create(['name_major' => $request->name_major]);
        return back()->with('success', 'Jurusan baru berhasil ditambahkan!');
    }

    public function destroyMajor(\App\Models\Major $major)
    {
        $major->delete();
        return back()->with('success', 'Jurusan berhasil dihapus!');
    }

    // ==========================================
    // --- FITUR: NAIK KELAS (MIGRATE KELAS) ---
    // ==========================================

    public function showMigrateKelas()
    {
        // Tampilkan siswa kelas 10, 11, dan 12 (semua bisa dimigrate)
        $students = Student::with(['user', 'major'])
            ->where(function ($q) {
                $q->where('class_name', 'like', '10 %')
                  ->orWhere('class_name', 'like', '11 %')
                  ->orWhere('class_name', 'like', '12 %');
            })
            ->orderBy('class_name')
            ->get();

        return view('admin.students.migrate_kelas', compact('students'));
    }

    public function processMigrateKelas(Request $request)
    {
        $request->validate([
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $count = 0;

        foreach ($request->student_ids as $studentId) {
            $student = Student::find($studentId);
            if (!$student) continue;

            // Ambil angka grade pertama dari class_name (e.g. "10" dari "10 TKJ 1")
            $parts  = explode(' ', $student->class_name, 2); // ["10", "TKJ 1"]
            $grade  = (int) ($parts[0] ?? 0);
            $suffix = trim($parts[1] ?? '');    // "Teknik Komputer dan Jaringan 1"

            if ($grade === 10 || $grade === 11) {
                // Naik ke kelas berikutnya
                $newClassName = ($grade + 1) . ' ' . $suffix;
                $student->update(['class_name' => $newClassName]);
                $count++;
            } elseif ($grade === 12) {
                // Kelas 12 → Telah Lulus
                $student->update(['class_name' => 'Lulus']);
                $count++;
            }
        }

        return redirect()->route('admin.students')
            ->with('success', "Berhasil menaikkan kelas untuk {$count} siswa!");
    }
}