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
Route::get('/dashboard', [HomeController::class, 'index'])->name(name: 'users.dashboard');
Route::get('/trang-chu', [HomeController::class, 'index'])->name('trang-chu');
Route::get('/sign-in', [HomeController::class, 'sign_in'])->name('sign_in');
Route::get('/home-logout', [HomeController::class, 'home_logout'])->name('home_logout');

Route::get('/login', [HomeController::class, 'sign_in'])->name('login');
Route::get('/sign-up', [HomeController::class, 'sign_up'])->name('sign_up');
Route::post('/home-dashboard', [HomeController::class,'home_dashboard'])->name('home_dashboard');

Route::post('/sign-up', [HomeController::class, 'register']);

//Nav
Route::get('/staff', [HomeController::class, 'staff'])->name('users.staff');
Route::get('/locations', [HomeController::class, 'locations'])->name('users.locations');
Route::get('/patients', [HomeController::class, 'patients'])->name('users.patients');
Route::get('/appointments', [HomeController::class, 'appointments'])->name('users.appointments');


Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [AdminController::class, 'show_dashboard'])->name('show_dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::post('/admin-dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
    //staff
    Route::get('/staff', [StaffController::class,'staff'])->name('staff');
    Route::post('/staff/create', [StaffController::class, 'createDoctor'])->name('admin.createDoctor');
    Route::put('/staff/edit/{id}', [StaffController::class, 'editDoctor'])->name('admin.editDoctor'); // Route chỉnh sửa
    Route::delete('/staff/delete/{id}', [StaffController::class, 'deleteDoctor'])->name('admin.deleteDoctor'); // Route xóa
    Route::get('/staff/doctors-list', [StaffController::class, 'getDoctorsList'])->name('admin.getDoctorsList');

    //appointment
    Route::get('/appointment', [AppointmentController::class,'appointment'])->name('appointment');
    Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::put('/appointment/{id}', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::delete('/appointment/{id}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');

    //Lab
    Route::get('/lab', [LabController::class,'lab'])->name('lab');
    Route::get('/lab/details/{id}', [LabController::class, 'show'])->name('admin.lab.details');
    Route::post('/lab-type/store', [LabController::class, 'storeLabType']) -> name('admin.storeLabType');
    Route::put('/lab-type/update/{id}', [LabController::class, 'updateLabType'])-> name('admin.updateLabType');
    Route::delete('/lab-type/delete/{id}', [LabController::class, 'deleteLabType']) -> name('admin.deleteLabType');

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
Route::prefix('doctor')->middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('doctor.dashboard');

    Route::get('/appointments', [App\Http\Controllers\Doctor\AppointmentController::class, 'index']) ->name('doctor.appointments');

    Route::get('/lab',[App\Http\Controllers\Doctor\LabController::class,'lab']) -> name('doctor.lab');

    Route::get('/patients',[App\Http\Controllers\Doctor\PatientController::class,'index']) -> name('doctor.patients');   
    Route::get('/patients-details/{id}',[App\Http\Controllers\Doctor\PatientController::class,'show']) -> name('doctor.patients.show');
    
    Route::get('/pharmacy',[App\Http\Controllers\Doctor\PharmacyController::class,'index']) -> name('doctor.pharmacy');
  
    Route::get('/treatments',[App\Http\Controllers\Doctor\TreatmentController::class,'index']) -> name('doctor.treatments');
   

    Route::get('/profile', function() { return 1; })->name('doctor.profile');
    Route::get('/settings', function() { return 1; })->name('doctor.settings');
    // Route::get('/logout', function() { return 1; })->name('doctor.logout');
    Route::get('/logout', [HomeController::class, 'home_logout'])->name('doctor.logout');

});
// ->middleware('auth')




// Patient - UI
Route::prefix('patient')->middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Patient\PatientController::class, 'index']) ->name('patient.dashboard');

    Route::get('/profile', [App\Http\Controllers\Patient\PatientController::class, 'profile'])->name('patient.profile');
    Route::get('/settings', [App\Http\Controllers\Patient\PatientController::class, 'settings'])->name('patient.settings');
    Route::get('/logout', [App\Http\Controllers\Patient\PatientController::class, 'logout'])->name('patient.logout');

    Route::get('/appointments', [App\Http\Controllers\Patient\AppointmentController::class, 'index']) ->name('patient.appointments');

    Route::get('/treatments',[App\Http\Controllers\Patient\TreatmentController::class,'index']) -> name('patient.treatments');

    Route::get('/lab',[App\Http\Controllers\Patient\LabController::class,'index']) -> name('patient.lab');

    Route::get('/pharmacy*',[App\Http\Controllers\Patient\PharmacyController::class,'index']) -> name('patient.pharmacy');


});
// ->middleware('auth')


  
// ->middleware('auth')