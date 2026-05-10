<?php

use App\Http\Controllers\IncidentCategoryController;
use App\Http\Controllers\StudentAbsenceController;
use App\Http\Controllers\StudentIncidentController;
use App\Http\Controllers\StudentObservationController;
use App\Http\Controllers\StudentSupportController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Models\StudentAbsence;
use App\Models\StudentIncident;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade; // Importante para que funcione el render

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/subjects/{id}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    Route::get('/majors', [App\Http\Controllers\MajorController::class, 'index'])->name('majors.index');
    Route::get('/majors/create', [App\Http\Controllers\MajorController::class, 'create'])->name('majors.create');
    Route::post('/majors', [App\Http\Controllers\MajorController::class, 'store'])->name('majors.store');
    Route::get('/majors/{major}/edit', [App\Http\Controllers\MajorController::class, 'edit'])->name('majors.edit');
    Route::put('/majors/{major}', [App\Http\Controllers\MajorController::class, 'update'])->name('majors.update');
    Route::delete('/majors/{major}', [App\Http\Controllers\MajorController::class, 'destroy'])->name('majors.destroy');

    Route::resource('users', UserController::class);

    Route::resource('incident_categories', IncidentCategoryController::class);

    Route::resource('student_absences', StudentAbsenceController::class)->except(['show']);
    Route::get('student_absences/events', [StudentAbsenceController::class, 'getEvents'])->name('absences.events');
    Route::get('student_absences/search-students', [StudentAbsenceController::class, 'searchStudents'])->name('absences.search-students');

    Route::resource('student_incidents', StudentIncidentController::class);

    Route::resource('student_observations', StudentObservationController::class);

    Route::resource('student_supports', StudentSupportController::class);
});


require __DIR__ . '/settings.php';
