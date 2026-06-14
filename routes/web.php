<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\StudentManagementController;


Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'teacher'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/students', [AttendanceController::class, 'getStudents'])->name('attendance.students');

    // All specific /students/* routes BEFORE the wildcard {student}
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('/students/suggestions', [StudentController::class, 'suggestions'])->name('students.suggestions');
    Route::get('/students/manage', [StudentManagementController::class, 'index'])->name('students.manage.index');
    Route::get('/students/manage/create', [StudentManagementController::class, 'create'])->name('students.manage.create');
    Route::post('/students/manage', [StudentManagementController::class, 'store'])->name('students.manage.store');
    Route::get('/students/manage/{student}/edit', [StudentManagementController::class, 'edit'])->name('students.manage.edit');
    Route::put('/students/manage/{student}', [StudentManagementController::class, 'update'])->name('students.manage.update');
    Route::delete('/students/manage/{student}', [StudentManagementController::class, 'destroy'])->name('students.manage.destroy');

    // Wildcard route LAST
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('teachers', TeacherController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';