<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Login;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TeacherStudentController;
use App\Http\Controllers\UserDirectoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\StudentAssignmentController;
use App\Http\Controllers\TeacherChallengeController;
use App\Http\Controllers\StudentChallengeController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [Login::class, 'show'])->name('login');
Route::post('/login', [Login::class, 'handle'])->name('login.post');
Route::post('/logout', [Login::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserDirectoryController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserDirectoryController::class, 'show'])->name('users.show');

    Route::post('/users/{id}/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::post('/messages/{message}/update', [MessageController::class, 'update'])->name('messages.update');
    Route::post('/messages/{message}/delete', [MessageController::class, 'destroy'])->name('messages.destroy');
});

Route::middleware(['auth', 'teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/home', [TeacherController::class, 'dashboard'])->name('home');

        Route::get('/students', [TeacherStudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [TeacherStudentController::class, 'create'])->name('students.create');
        Route::post('/students', [TeacherStudentController::class, 'store'])->name('students.store');
        Route::get('/students/{id}/edit', [TeacherStudentController::class, 'edit'])->name('students.edit');
        Route::post('/students/{id}', [TeacherStudentController::class, 'update'])->name('students.update');
        Route::post('/students/{id}/delete', [TeacherStudentController::class, 'destroy'])->name('students.destroy');

        Route::get('/assignments', [TeacherAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/create', [TeacherAssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/assignments', [TeacherAssignmentController::class, 'store'])->name('assignments.store');
        Route::get('/assignments/{assignment}/submissions', [TeacherAssignmentController::class, 'submissions'])
            ->name('assignments.submissions');
        Route::get('/assignments/{assignment}/submissions/{submission}/download', [TeacherAssignmentController::class, 'downloadSubmission'])
            ->name('assignments.submissions.download');

        Route::get('/challenges', [TeacherChallengeController::class, 'index'])->name('challenges');
        Route::post('/challenges', [TeacherChallengeController::class, 'store'])->name('challenges.store');
    });

Route::middleware(['auth', 'student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/home', [StudentController::class, 'dashboard'])->name('home');

        Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile');
        Route::post('/profile', [StudentProfileController::class, 'update'])->name('profile.update');

        Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/{assignment}/download', [StudentAssignmentController::class, 'download'])
            ->name('assignments.download');
        Route::post('/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])
            ->name('assignments.submit');

        Route::get('/challenges', [StudentChallengeController::class, 'index'])->name('challenges');
        Route::post('/challenges/{challenge}/answer', [StudentChallengeController::class, 'answer'])
            ->name('challenges.answer');
    });
