<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuruController;

// ==========================================
// RUTE AUTHENTICATION (LOGIN & LOGOUT)
// ==========================================
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

// ==========================================
// RUTE KHUSUS ADMIN
// ==========================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Manajemen Users (Tab 2)
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset_password'); // <- Dipindah ke dalam grup Admin

    // Manajemen Siswa (Tab 3)
    Route::get('/students', [AdminController::class, 'indexStudents'])->name('admin.students');
    Route::get('/students/create', [AdminController::class, 'createStudent'])->name('admin.students.create');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');
    Route::delete('/students/{student}', [AdminController::class, 'destroyStudent'])->name('admin.students.destroy');
    Route::post('/students/{student}/reset-password', [AdminController::class, 'resetStudentPassword'])->name('admin.students.reset_password'); // <- Dipindah ke dalam grup Admin
    
    // Manajemen Nilai Siswa (Oleh Admin)
    Route::get('/students/{student}/scores', [AdminController::class, 'showStudentScores'])->name('admin.students.scores');
    Route::post('/students/{student}/scores', [AdminController::class, 'storeStudentScore'])->name('admin.students.scores.store');

    // Manajemen Mata Pelajaran (Tab 4)
    Route::get('/courses', [AdminController::class, 'indexCourses'])->name('admin.courses');
    Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('admin.courses.create');
    Route::post('/courses', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
    Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('admin.courses.destroy');

    // Manajemen Tugas Mengajar Guru
    Route::get('/gurus/{user}/detail', [AdminController::class, 'showGuruDetail'])->name('admin.gurus.detail');
    Route::post('/gurus/{user}/assign-course', [AdminController::class, 'assignCourseToGuru'])->name('admin.gurus.assign_course');
    Route::delete('/gurus/{user}/remove-course/{course}', [AdminController::class, 'removeCourseFromGuru'])->name('admin.gurus.remove_course');
    // Kelola Kategori Nilai Dinamis
    Route::post('/score-categories', [AdminController::class, 'storeScoreCategory'])->name('admin.score_categories.store');
    Route::delete('/score-categories/{category}', [AdminController::class, 'destroyScoreCategory'])->name('admin.score_categories.destroy');

    Route::post('/score-categories', [AdminController::class, 'storeScoreCategory'])->name('admin.score_categories.store');
    Route::delete('/score-categories/{category}', [AdminController::class, 'destroyScoreCategory'])->name('admin.score_categories.destroy');

    Route::get('/students/{student}/edit', [AdminController::class, 'editStudent'])->name('admin.students.edit');
    Route::put('/students/{student}', [AdminController::class, 'updateStudent'])->name('admin.students.update');

    Route::get('/students/{student}/courses', [App\Http\Controllers\AdminController::class, 'manageStudentCourses'])->name('admin.students.manage_courses');
    Route::post('/students/{student}/courses', [App\Http\Controllers\AdminController::class, 'updateStudentCourses'])->name('admin.students.update_courses');

    Route::get('/export-pdf/{student}', [StudentController::class, 'exportPdf'])->name('export.pdf');

    Route::post('/students/import', [AdminController::class, 'importStudents'])->name('admin.students.import');
});

// ==========================================
// RUTE KHUSUS GURU
// ==========================================
Route::middleware(['auth'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
    
    // Fitur Input Nilai (Dipindah ke dalam grup Auth Guru)
    Route::get('/course/{course}/input-nilai', [GuruController::class, 'inputNilai'])->name('guru.input_nilai');
    Route::post('/course/{course}/save-nilai', [GuruController::class, 'saveNilai'])->name('guru.save_nilai');
    Route::get('/export-pdf/{student}', [StudentController::class, 'exportPdf'])->name('export.pdf');
});

// ==========================================
// RUTE KHUSUS SISWA
// ==========================================
Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/profile', [StudentController::class, 'profile'])->name('student.profile'); 
});