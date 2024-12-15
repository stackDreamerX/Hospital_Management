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
use Illuminate\Support\Facades\Route;

//FrontEnd
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/trang-chu', [HomeController::class, 'index'])->name('trang-chu');
Route::get('/sign-in', [HomeController::class, 'sign_in'])->name('sign_in');
Route::get('/home-logout', [HomeController::class, 'home_logout'])->name('home_logout');
Route::post('/home-dashboard', [HomeController::class,'home_dashboard'])->name('home_dashboard'); 
Route::get('/sign-up', [HomeController::class, 'sign_up'])->name('sign_up');
Route::post('/sign-up', [HomeController::class, 'register']);

//BackEnd
Route::get('/admin', [AdminController::class,'index'])->name('admin');
Route::get('/dashboard', [AdminController::class,'show_dashboard'])->name('show_dashboard');
Route::get('/logout', [AdminController::class,'logout'])->name('logout');
Route::post('/admin-dashboard', [AdminController::class,'dashboard'])->name('dashboard'); 

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
