<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontDeskController;
use App\Http\Controllers\AttendantController;
use App\Http\Controllers\ClearanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Main Dashboard
Route::get('/', [FrontDeskController::class, 'index'])->name('frontdesk.index');

// Doctors & Specializations
Route::get('/doctors/specialization/{id}', [FrontDeskController::class, 'getDoctorsBySpecialization'])
    ->name('doctors.by-specialization');
Route::get('/doctors/{id}', [FrontDeskController::class, 'getDoctorDetails'])
    ->name('doctors.details');

// Tests
Route::get('/tests', [FrontDeskController::class, 'getTests'])
    ->name('tests.index');

// Surgeries
Route::get('/surgeries', [FrontDeskController::class, 'getSurgeries'])
    ->name('surgeries.index');

// Patient Search
Route::get('/patients/search', [FrontDeskController::class, 'searchPatient'])
    ->name('patients.search');
Route::get('/patients/{id}', [FrontDeskController::class, 'getPatientDetails'])
    ->name('patients.details');

// Ward Bed Status
Route::get('/departments/{id}/beds', [FrontDeskController::class, 'getDepartmentBeds'])
    ->name('departments.beds');
Route::get('/beds/{id}', [FrontDeskController::class, 'getBedDetails'])
    ->name('beds.details');

// Attendant Management Routes
Route::get('/attendants/card/{cardNumber}', [AttendantController::class, 'getAttendantByCard'])
    ->name('attendants.by-card');
Route::post('/attendants', [AttendantController::class, 'storeAttendant'])
    ->name('attendants.store');
Route::post('/attendants/{id}/toggle', [AttendantController::class, 'toggleAttendantStatus'])
    ->name('attendants.toggle');
Route::get('/attendants/{id}/logs', [AttendantController::class, 'getAttendantLogs'])
    ->name('attendants.logs');
Route::get('/patients/{id}/attendants', [AttendantController::class, 'getPatientAttendants'])
    ->name('patients.attendants');

// Patient Clearance Routes
Route::get('/clearance/search', [ClearanceController::class, 'searchForClearance'])
    ->name('clearance.search');
Route::get('/clearance/{patientId}', [ClearanceController::class, 'getClearanceReport'])
    ->name('clearance.report');
Route::post('/clearance/{patientId}', [ClearanceController::class, 'updateClearance'])
    ->name('clearance.update');
Route::post('/clearance/{patientId}/final', [ClearanceController::class, 'markFinalClearance'])
    ->name('clearance.final');
