<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Models\Score;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Mengambil data rata-rata nilai per mapel untuk tabel
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
        $courses = Course::all();
        return view('admin.courses', compact('courses'));
    }

    public function createCourse()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.courses.create', compact('gurus'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:courses',
            'guru_ids' => 'required|array', 
            'guru_ids.*' => 'exists:users,id'
        ]);

        $course = Course::create([
            'name' => $request->name,
        ]);

        $course->teachers()->attach($request->guru_ids);

        return redirect()->route('admin.courses')->with('success', 'Mata Pelajaran berhasil ditambahkan dan ditugaskan!');
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
        
        return view('admin.students.index', compact('students'));
    }

    public function manageStudentCourses(Student $student)
    {
        $allCourses = Course::all();
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
        $student->load('user', 'scores.course');
        
        if (\Illuminate\Support\Facades\Auth::user()->role == 'admin') {
            // Jika Admin: Tampilkan semua mapel yang sudah DIBERIKAN kepada siswa tersebut
            $courses = $student->courses;
        } else {
            // Jika Guru: Tampilkan HANYA mapel yang diajarkan guru ini DAN yang diambil siswa ini
            $guruCourses = \Illuminate\Support\Facades\Auth::user()->courses;
            $courses = $student->courses->intersect($guruCourses);
        }

        return view('admin.students.scores', compact('student', 'courses'));
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

        $user->load('courses');
        
        $assignedCourseIds = $user->courses->pluck('id');
        $availableCourses = Course::whereNotIn('id', $assignedCourseIds)->get();

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
}