<?php

use App\Http\Controllers\HomeController;
// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\WardController;
use App\Http\Controllers\Admin\TreatmentController as AdminTreatmentController;
use App\Http\Controllers\Admin\PharmacyController as AdminPharmacyController;
use App\Http\Controllers\Admin\LabController as AdminLabController;

// Doctor Controllers
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\PatientController as DoctorPatientController;
use App\Http\Controllers\Doctor\TreatmentController as DoctorTreatmentController;
use App\Http\Controllers\Doctor\LabController as DoctorLabController;
use App\Http\Controllers\Doctor\PharmacyController as DoctorPharmacyController;

// Patient Controllers
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\TreatmentController as PatientTreatmentController;
use App\Http\Controllers\Patient\LabController as PatientLabController;
use App\Http\Controllers\Patient\PharmacyController as PatientPharmacyController;

use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/trang-chu', [HomeController::class, 'index'])->name('trang-chu');
Route::get('/sign-in', [HomeController::class, 'sign_in'])->name('sign_in');
Route::get('/sign-up', [HomeController::class, 'sign_up'])->name('sign_up');
Route::post('/sign-up', [HomeController::class, 'register']);
Route::post('/home-dashboard', [HomeController::class, 'home_dashboard'])->name('home_dashboard');
Route::get('/home-logout', [HomeController::class, 'home_logout'])->name('home_logout');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [AdminController::class, 'show_dashboard'])->name('show_dashboard');
    Route::post('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

    // Staff Management
    Route::get('/staff', [StaffController::class, 'staff'])->name('staff');

    // Patient Management
    Route::get('/patients', [AdminPatientController::class, 'patient'])->name('admin.patients');

    // Appointment Management
    Route::get('/appointments', [AdminAppointmentController::class, 'appointment'])->name('admin.appointments');
    Route::post('/appointments', [AdminAppointmentController::class, 'store'])->name('appointment.store');
    Route::put('/appointments/{id}', [AdminAppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointments/{id}', [AdminAppointmentController::class, 'destroy'])->name('appointment.destroy');

    // Ward Management
    Route::get('/wards', [WardController::class, 'ward'])->name('admin.wards');

    // Treatment Management
    Route::get('/treatments', [AdminTreatmentController::class, 'treatment'])->name('admin.treatments');

    // Pharmacy Management
    Route::get('/pharmacy', [AdminPharmacyController::class, 'pharmacy'])->name('admin.pharmacy');

    // Lab Management
    Route::get('/lab', [AdminLabController::class, 'lab'])->name('admin.lab');
});

// Doctor Routes
Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    
    // Appointments
    Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('appointments');
    Route::put('/appointments/{id}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Patients
    Route::get('/patients', [DoctorPatientController::class, 'index'])->name('patients');
    Route::get('/patients/{id}', [DoctorPatientController::class, 'show'])->name('patients.show');

    // Treatments
    Route::get('/treatments', [DoctorTreatmentController::class, 'index'])->name('treatments');
    Route::post('/treatments', [DoctorTreatmentController::class, 'store'])->name('treatments.store');
    Route::put('/treatments/{id}', [DoctorTreatmentController::class, 'update'])->name('treatments.update');

    // Lab Tests
    Route::get('/lab', [DoctorLabController::class, 'lab'])->name('lab');
    Route::post('/lab', [DoctorLabController::class, 'store'])->name('lab.store');
    Route::put('/lab/{id}', [DoctorLabController::class, 'update'])->name('lab.update');

    // Pharmacy/Prescriptions
    Route::get('/pharmacy', [DoctorPharmacyController::class, 'index'])->name('pharmacy');
});

// Patient Routes
Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
    
    // Appointments
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/appointments/{id}', [PatientAppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{id}', [PatientAppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Treatments
    Route::get('/treatments', [PatientTreatmentController::class, 'index'])->name('treatments');
    Route::get('/treatments/{id}', [PatientTreatmentController::class, 'show'])->name('treatments.show');
    Route::get('/treatments/{id}/report', [PatientTreatmentController::class, 'downloadReport'])->name('treatments.report');

    // Lab Tests
    Route::get('/lab', [PatientLabController::class, 'index'])->name('lab');
    Route::get('/lab/{id}/report', [PatientLabController::class, 'downloadReport'])->name('lab.report');

    // Prescriptions
    Route::get('/pharmacy', [PatientPharmacyController::class, 'index'])->name('pharmacy');
    Route::get('/pharmacy/{id}', [PatientPharmacyController::class, 'show'])->name('pharmacy.show');
    Route::get('/pharmacy/{id}/download', [PatientPharmacyController::class, 'downloadPrescription'])->name('pharmacy.download');
});
