<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\WardController;
use App\Http\Controllers\Admin\TreatmentController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\RatingController as AdminRatingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Patient\RatingController as PatientRatingController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;



//FrontEnd - users
Route::get('/', [HomeController::class, 'index'])->name(name: 'users');
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
Route::get('/search-doctors', [HomeController::class, 'search'])->name('users.search.doctors');

// Public doctor ratings
Route::get('/doctors/{id}/ratings', [RatingController::class, 'doctorRatings'])->name('doctor.public.ratings');

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
    Route::post('/lab/create', [LabController::class, 'store'])->name('admin.lab.create');
    Route::put('/lab/{id}/updateLab', [LabController::class, 'updateLab'])->name('admin.lab.updateLab');
    Route::delete('/lab/{id}/delete', [LabController::class, 'destroyLab'])->name('admin.lab.delete');

    //ward
    Route::get('/ward', [WardController::class,'ward'])->name('admin.ward');

    //treatment
    Route::get('/treatment', [TreatmentController::class,'index'])->name('admin.treatment');
    Route::get('/treatments/{id}', [TreatmentController::class, 'show'])->name('admin.treatment.show');
    Route::delete('/treatments/{id}', [TreatmentController::class, 'destroy'])->name('admin.treatment.destroy');
    
    //pharmacy
    Route::get('/pharmacy', [PharmacyController::class,'index'])->name('admin.pharmacy');
    Route::get('/pharmacy//{id}', [PharmacyController::class, 'show'])->name('admin.prescription.show');
    
    //patient
    Route::get('/patient', [PatientController::class, 'index'])->name('admin.patient'); // Danh sách user
    Route::put('/patient/{id}', [PatientController::class, 'update'])->name('admin.patient.update'); // Cập nhật user
    Route::delete('/patient/{id}', [PatientController::class, 'destroy'])->name('admin.patient.destroy'); // Xóa user
    Route::post('/patient', [PatientController::class, 'store'])->name('admin.users.store');

    // Ratings
    Route::get('/ratings', [AdminRatingController::class, 'index'])->name('admin.ratings.index');
    Route::get('/ratings/dashboard', [AdminRatingController::class, 'dashboard'])->name('admin.ratings.dashboard');
    Route::put('/ratings/{id}/status', [AdminRatingController::class, 'updateStatus'])->name('admin.ratings.updateStatus');
    Route::delete('/ratings/{id}', [AdminRatingController::class, 'destroy'])->name('admin.ratings.destroy');
    Route::get('/doctors/{id}/ratings', [AdminRatingController::class, 'doctorRatings'])->name('admin.doctor.ratings');
});
// ->middleware('auth')



// Doctor - UI
Route::prefix('doctor')->middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('doctor.dashboard');

    Route::get('/appointments', [App\Http\Controllers\Doctor\AppointmentController::class, 'index']) ->name('doctor.appointments');
    Route::post('/appointments/{id}/updateStatus', [App\Http\Controllers\Doctor\AppointmentController::class, 'updateStatus'])->name('doctor.appointments.updateStatus');



    Route::get('/lab', [App\Http\Controllers\Doctor\LabController::class,'lab'])->name('doctor.lab');
    Route::get('/lab/details/{id}', [App\Http\Controllers\Doctor\LabController::class, 'show'])->name('doctor.lab.details');
    Route::post('/lab/create', [App\Http\Controllers\Doctor\LabController::class, 'store'])->name('doctor.lab.create');
    Route::put('/lab/{id}/updateLab', [App\Http\Controllers\Doctor\LabController::class, 'updateLab'])->name('doctor.lab.updateLab');
    Route::delete('/lab/{id}/delete', [App\Http\Controllers\Doctor\LabController::class, 'destroyLab'])->name('doctor.lab.delete');

    Route::get('/patients',[App\Http\Controllers\Doctor\PatientController::class,'index']) -> name('doctor.patients');
    Route::get('/patients-details/{id}',[App\Http\Controllers\Doctor\PatientController::class,'show']) -> name('doctor.patients.show');

    Route::get('/pharmacy',[App\Http\Controllers\Doctor\PharmacyController::class,'index']) -> name('doctor.pharmacy');
    Route::post('/pharmacy/create', [App\Http\Controllers\Doctor\PharmacyController::class, 'store'])->name('doctor.pharmacy.create');
    Route::get('/pharmacy/details/{id}', [App\Http\Controllers\Doctor\PharmacyController::class, 'show'])->name('doctor.pharmacy.show');
    Route::delete('/pharmacy/{id}/delete', [App\Http\Controllers\Doctor\PharmacyController::class, 'destroy'])->name('doctor.pharmacy.destroy');
    Route::put('/pharmacy/{id}/updatePharmacy', [App\Http\Controllers\Doctor\PharmacyController::class, 'update'])->name('doctor.treatments.update');
    Route::put('/pharmacy//{id}/cancel', [App\Http\Controllers\Doctor\PharmacyController::class, 'cancel'])->name('doctor.pharmacy.cancel');



    Route::get('/treatments',[App\Http\Controllers\Doctor\TreatmentController::class,'index']) -> name('doctor.treatments');
    Route::post('/treatments/create', [App\Http\Controllers\Doctor\TreatmentController::class, 'store'])->name('doctor.treatment.store');
    Route::get('/treatments/details/{id}', [App\Http\Controllers\Doctor\TreatmentController::class, 'show'])->name('doctor.treatments.show');
    Route::put('/treatments/{id}/updateTreatment', [App\Http\Controllers\Doctor\TreatmentController::class, 'update'])->name('doctor.treatments.update');
    Route::delete('/treatments/{id}/delete', [App\Http\Controllers\Doctor\TreatmentController::class, 'destroy'])->name('doctor.treatments.destroy');

    Route::get('/profile', function() { return 1; })->name('doctor.profile');
    Route::get('/settings', function() { return 1; })->name('doctor.settings');
    Route::get('/logout', [HomeController::class, 'home_logout'])->name('doctor.logout');
    
    // Doctor ratings view
    Route::get('/my-ratings', [App\Http\Controllers\Doctor\RatingViewController::class, 'index'])->name('doctor.my.ratings');
});
// ->middleware('auth')


// Patient - UI
Route::prefix('patient')->middleware('auth')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Patient\PatientController::class, 'index']) ->name('patient.dashboard');

    Route::get('/profile', [App\Http\Controllers\Patient\PatientController::class, 'profile'])->name('patient.profile');
    Route::get('/settings', [App\Http\Controllers\Patient\PatientController::class, 'settings'])->name('patient.settings');
    Route::get('/logout', [App\Http\Controllers\Patient\PatientController::class, 'logout'])->name('patient.logout');

    Route::get('/appointments', [App\Http\Controllers\Patient\AppointmentController::class, 'index']) ->name('patient.appointments.index');
    Route::post('/appointments/create', [App\Http\Controllers\Patient\AppointmentController::class, 'store'])->name('patient.appointments.store');
    Route::get('/appointments/{id}', [App\Http\Controllers\Patient\AppointmentController::class, 'show'])->name('patient.appointments.show');
    Route::get('/appointments/detail/{id}', [App\Http\Controllers\Patient\AppointmentController::class, 'showDetail']) ->name('patient.appointments.showDetail');

    Route::put('/appointments/{id}/updateAppointment', [App\Http\Controllers\Patient\AppointmentController::class, 'update'])->name('patient.appointments.update');
    Route::delete('/appointments/{id}/delete', [App\Http\Controllers\Patient\AppointmentController::class, 'destroy'])->name('patient.appointments.destroy');


    Route::get('/treatments',[App\Http\Controllers\Patient\TreatmentController::class,'index']) -> name('patient.treatments');

    Route::get('/lab',[App\Http\Controllers\Patient\LabController::class,'index']) -> name('patient.lab');

    Route::get('/pharmacy*',[App\Http\Controllers\Patient\PharmacyController::class,'index']) -> name('patient.pharmacy');
    Route::get('/pharmacy/{id}', [App\Http\Controllers\Patient\PrescriptionController::class, 'show'])->name('patient.pharmacy.show');

    // Patient ratings
    Route::get('/ratings', [PatientRatingController::class, 'index'])->name('patient.ratings.index');
    Route::get('/appointments/{id}/rate', [PatientRatingController::class, 'create'])->name('patient.ratings.create');
    Route::post('/ratings', [PatientRatingController::class, 'store'])->name('patient.ratings.store');
    Route::get('/doctors/{id}/ratings', [PatientRatingController::class, 'viewDoctorRatings'])->name('patient.doctor.ratings');
});
// ->middleware('auth')



// ->middleware('auth')