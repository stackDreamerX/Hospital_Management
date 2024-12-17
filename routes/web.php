<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\WardController;
use App\Http\Controllers\Admin\TreatmentController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Doctor\DashboardController;
use Illuminate\Support\Facades\Route;



//FrontEnd - users 
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/trang-chu', [HomeController::class, 'index'])->name('trang-chu');
Route::get('/sign-in', [HomeController::class, 'sign_in'])->name('sign_in');
Route::get('/home-logout', [HomeController::class, 'home_logout'])->name('home_logout');
Route::post('/home-dashboard', [HomeController::class,'home_dashboard'])->name('home_dashboard'); 
Route::get('/sign-up', [HomeController::class, 'sign_up'])->name('sign_up');
Route::post('/sign-up', [HomeController::class, 'register']);


Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [AdminController::class, 'show_dashboard'])->name('show_dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::post('/admin-dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
    //staff
    Route::get('/staff', [StaffController::class,'staff'])->name('staff');

    //appointment
    Route::get('/appointment', [AppointmentController::class,'appointment'])->name('appointment');
    Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::put('/appointment/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/{id}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');

    //Lab
    Route::get('/lab', [LabController::class,'lab'])->name('lab');

    //ward
    Route::get('/ward', [WardController::class,'ward'])->name('ward');

    //treatment
    Route::get('/treatment', [TreatmentController::class,'treatment'])->name('treatment');

    //pharmacy
    Route::get('/pharmacy', [PharmacyController::class,'pharmacy'])->name('pharmacy');

    //patient
    Route::get('/patient', [PatientController::class,'patient'])->name('patient');

});
// ->middleware('auth')



// Doctor - UI
Route::prefix('doctor')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('doctor.dashboard');

    Route::get('/appointments', [App\Http\Controllers\Doctor\AppointmentController::class, 'index']) ->name('doctor.appointments');

    Route::get('/lab',[App\Http\Controllers\Doctor\LabController::class,'lab']) -> name('doctor.lab');

    Route::get('/patients',[App\Http\Controllers\Doctor\PatientController::class,'index']) -> name('doctor.patients');   
    Route::get('/patients-details/{id}',[App\Http\Controllers\Doctor\PatientController::class,'show']) -> name('doctor.patients.show');
    


    Route::get('/pharmacy',[App\Http\Controllers\Doctor\PharmacyController::class,'index']) -> name('doctor.pharmacy');

    Route::get('/staff',[App\Http\Controllers\Doctor\LabController::class,'index']) -> name('doctor.staff');

    Route::get('/treatments',[App\Http\Controllers\Doctor\TreatmentController::class,'index']) -> name('doctor.treatments');

    Route::get('/ward',[App\Http\Controllers\Doctor\LabController::class,'index']) -> name('doctor.ward');

});
// ->middleware('auth')

