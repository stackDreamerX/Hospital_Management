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
use App\Http\Controllers\WardBedController;
use App\Http\Controllers\PatientWardAllocationController;
use App\Http\Controllers\WardBedHistoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PatientMonitoringController;
use App\Http\Controllers\MedicationController;



//FrontEnd - users
Route::get('/', [HomeController::class, 'index'])->name(name: 'users');
Route::get('/dashboard', [HomeController::class, 'index'])->name(name: 'users.dashboard');

Route::get('/trang-chu', [HomeController::class, 'index'])->name('trang-chu');
Route::get('/sign-in', [HomeController::class, 'sign_in'])->name('sign_in');
Route::get('/home-logout', [HomeController::class, 'home_logout'])->name('home.logout');

Route::get('/login', [HomeController::class, 'sign_in'])->name('login');
Route::get('/sign-up', [HomeController::class, 'sign_up'])->name('sign_up');
Route::post('/home-dashboard', [HomeController::class,'home_dashboard'])->name('home_dashboard');

Route::post('/sign-up', [HomeController::class, 'register']);

//Nav
Route::get('/staff', [HomeController::class, 'staff'])->name('users.staff');
Route::get('/locations', [HomeController::class, 'locations'])->name('users.locations');
Route::get('/patients', [HomeController::class, 'patients'])->name('users.patients');
Route::get('/appointments', [HomeController::class, 'appointments'])->name('users.appointments');
Route::get('/search-doctors', [HomeController::class, 'searchDoctors'])->name('search.doctors');
Route::get('/doctor-profile/{id}', [HomeController::class, 'doctorProfile'])->name('doctor.public.profile');
Route::get('/doctor/{id}/schedule', [App\Http\Controllers\BookingController::class, 'showDoctorSchedule'])->name('doctor.schedule');

// Booking routes without auth middleware
Route::get('/doctor/{id}/booking/{slot?}', [App\Http\Controllers\BookingController::class, 'showBookingForm'])->name('doctor.booking');

// Protected booking routes
Route::middleware(['auth'])->group(function () {
Route::post('/booking/store', [App\Http\Controllers\BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/thank-you/{appointmentId}', [App\Http\Controllers\BookingController::class, 'thankYou'])->name('booking.thank-you');
});

// Payment routes
Route::get('/vnpay/callback', [App\Http\Controllers\VNPayController::class, 'callback'])->name('vnpay.callback');
Route::get('/zalopay/callback', [App\Http\Controllers\ZaloPayController::class, 'callback'])->name('zalopay.callback');

// Public doctor ratings
Route::get('/doctors/{id}/ratings', [RatingController::class, 'doctorRatings'])->name('doctor.public.ratings');

// API routes for reviews
Route::get('/api/doctor/{doctorId}/reviews', [App\Http\Controllers\ReviewController::class, 'apiGetDoctorReviews']);

// Doctor review routes
Route::middleware(['auth'])->group(function() {
    Route::post('/doctor/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('doctor.review.store');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'show_dashboard'])->name('admin');
    Route::get('/dashboard', [AdminController::class, 'show_dashboard'])->name('admin.dashboard');
    Route::get('/logout', [HomeController::class, 'home_logout'])->name('logout');
    // Route::post('/adminDashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

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
    Route::get('/ward', [WardController::class,'index'])->name('admin.ward');
    Route::post('/wards', [WardController::class, 'store'])->name('admin.wards.store');
    Route::post('/wards/{id}', [WardController::class, 'update'])->name('admin.wards.update');
    Route::delete('/wards/{id}', [WardController::class, 'destroy'])->name('admin.wards.destroy');

    //treatment
    Route::get('/treatment', [TreatmentController::class,'index'])->name('admin.treatment');
    Route::get('/treatments/{id}', [TreatmentController::class, 'show'])->name('admin.treatment.show');
    Route::delete('/treatments/{id}', [TreatmentController::class, 'destroy'])->name('admin.treatment.destroy');

    //pharmacy
    Route::get('/pharmacy', [PharmacyController::class,'index'])->name('admin.pharmacy');
    Route::get('/pharmacy//{id}', [PharmacyController::class, 'show'])->name('admin.prescription.show');

    //patient
    Route::get('/patient', [PatientController::class, 'index'])->name('admin.patient'); // Danh sách user
    Route::post('/patient/{id}', [PatientController::class, 'update'])->name('admin.patient.update'); // Cập nhật user
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

    // Doctor schedule management
    Route::get('/schedule', [App\Http\Controllers\Doctor\ScheduleController::class, 'index'])->name('doctor.schedule.index');
    Route::post('/schedule', [App\Http\Controllers\Doctor\ScheduleController::class, 'store'])->name('doctor.schedule.store');
    Route::delete('/schedule/{id}', [App\Http\Controllers\Doctor\ScheduleController::class, 'destroy'])->name('doctor.schedule.destroy');



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
    Route::get('/pharmacy/download-pdf/{id}', [App\Http\Controllers\Doctor\PharmacyController::class, 'downloadPdf'])->name('doctor.pharmacy.download-pdf');
    Route::delete('/pharmacy/{id}/delete', [App\Http\Controllers\Doctor\PharmacyController::class, 'destroy'])->name('doctor.pharmacy.destroy');
    Route::put('/pharmacy/{id}/updatePharmacy', [App\Http\Controllers\Doctor\PharmacyController::class, 'update'])->name('doctor.treatments.update');
    Route::delete('/pharmacy/{id}/cancel', [App\Http\Controllers\Doctor\PharmacyController::class, 'cancel'])->name('doctor.pharmacy.cancel');



    Route::get('/treatments',[App\Http\Controllers\Doctor\TreatmentController::class,'index']) -> name('doctor.treatments');
    Route::post('/treatments/create', [App\Http\Controllers\Doctor\TreatmentController::class, 'store'])->name('doctor.treatment.store');
    Route::get('/treatments/details/{id}', [App\Http\Controllers\Doctor\TreatmentController::class, 'show'])->name('doctor.treatments.show');
    Route::post('/treatments/{id}/updateTreatment', [App\Http\Controllers\Doctor\TreatmentController::class, 'update'])->name('doctor.treatments.update');
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

// Feedback routes
Route::middleware(['auth'])->group(function () {
    Route::get('/feedback/create', [App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/thank-you', [App\Http\Controllers\FeedbackController::class, 'thankYou'])->name('feedback.thank-you');
    Route::get('/my-feedback', [App\Http\Controllers\FeedbackController::class, 'userFeedback'])->name('feedback.user');
});

// Admin feedback routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'adminDashboard'])->name('admin.feedback');
    Route::get('/feedback/index', [App\Http\Controllers\FeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::get('/feedback/{feedback}', [App\Http\Controllers\FeedbackController::class, 'show'])->name('feedback.show');
    Route::get('/feedback/{feedback}/edit', [App\Http\Controllers\FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/{feedback}', [App\Http\Controllers\FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [App\Http\Controllers\FeedbackController::class, 'destroy'])->name('feedback.destroy');
    Route::patch('/feedback/{feedback}/status', [App\Http\Controllers\FeedbackController::class, 'updateStatus'])->name('feedback.updateStatus');
});

// Public feedback route
Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'publicFeedback'])->name('feedback.public');

// Bed Management Routes
// Route::middleware(['auth'])->prefix('admin')->group(function () {
    // WardBed routes
    Route::get('/beds', [WardBedController::class, 'index'])->name('beds.index');

    Route::get('/beds/create', [WardBedController::class, 'create'])->name('beds.create');
    Route::post('/beds', [WardBedController::class, 'store'])->name('beds.store');
    Route::get('/beds/{bed}', [WardBedController::class, 'show'])->name('beds.show');
    Route::get('/beds/{bed}/edit', [WardBedController::class, 'edit'])->name('beds.edit');
    Route::put('/beds/{bed}', [WardBedController::class, 'update'])->name('beds.update');
    Route::delete('/beds/{bed}', [WardBedController::class, 'destroy'])->name('beds.destroy');
    Route::put('/beds/{bed}/change-status', [WardBedController::class, 'changeStatus'])->name('beds.change-status');
    Route::get('/available-beds', [WardBedController::class, 'getAvailableBeds'])->name('beds.available');

    // Patient allocation routes
    Route::get('/allocations', [PatientWardAllocationController::class, 'index'])->name('allocations.index');
    Route::get('/allocations/create', [PatientWardAllocationController::class, 'create'])->name('allocations.create');
    Route::post('/allocations', [PatientWardAllocationController::class, 'store'])->name('allocations.store');
    Route::get('/allocations/{allocation}', [PatientWardAllocationController::class, 'show'])->name('allocations.show');
    Route::get('/allocations/{allocation}/edit', [PatientWardAllocationController::class, 'edit'])->name('allocations.edit');
    Route::put('/allocations/{allocation}', [PatientWardAllocationController::class, 'update'])->name('allocations.update');
    Route::put('/allocations/{allocation}/discharge', [PatientWardAllocationController::class, 'discharge'])->name('allocations.discharge');
    Route::get('/available-patients', [PatientWardAllocationController::class, 'getAvailablePatients'])->name('allocations.available-patients');

    // Debug route to check patients
    Route::get('/debug-patients', [PatientWardAllocationController::class, 'debugPatients'])->name('debug-patients');

    // Bed history routes
    Route::get('/bed-history', [WardBedHistoryController::class, 'index'])->name('bed-history.index');
    Route::get('/bed-history/{history}', [WardBedHistoryController::class, 'show'])->name('bed-history.show');
    Route::get('/beds/{bed}/history', [WardBedHistoryController::class, 'forBed'])->name('bed-history.for-bed');
    Route::get('/bed-utilization-report', [WardBedHistoryController::class, 'report'])->name('bed-history.report');
// });

// Add direct routes for appointments using HomeController instead of PatientController
Route::middleware(['auth'])->group(function () {
    // Appointment routes
    Route::post('/appointments/create', [HomeController::class, 'appointmentStore'])->name('users.appointments.store');
    Route::get('/appointments/detail/{id}', [HomeController::class, 'appointmentShowDetail'])->name('users.appointments.showDetail');
    Route::get('/appointments/{id}', [HomeController::class, 'appointmentShow'])->name('users.appointments.show');
    Route::put('/appointments/{id}/update', [HomeController::class, 'appointmentUpdate'])->name('users.appointments.update');
    Route::delete('/appointments/{id}/delete', [HomeController::class, 'appointmentDestroy'])->name('users.appointments.destroy');

    // User prescriptions route
    Route::get('/prescriptions', [HomeController::class, 'userPrescriptions'])->name('users.prescriptions');
    Route::get('/prescriptions/{id}', [HomeController::class, 'prescriptionShow'])->name('users.prescriptions.show');
    Route::get('/prescriptions/{id}/download-pdf', [HomeController::class, 'downloadPrescriptionPdf'])->name('users.prescriptions.download-pdf');

    // User profile route
    Route::get('/profile', [HomeController::class, 'userProfile'])->name('users.profile');
    Route::put('/profile/update', [HomeController::class, 'updateProfile'])->name('users.profile.update');

    // User hospitalizations history
    Route::get('/hospitalizations', [HomeController::class, 'userHospitalizations'])->name('users.hospitalizations');
    Route::get('/hospitalizations/{allocation}', [HomeController::class, 'hospitalizationShow'])->name('users.hospitalizations.show');
});

// Patient Ward Allocations
Route::prefix('allocations')->name('allocations.')->group(function () {
    Route::get('/', [PatientWardAllocationController::class, 'index'])->name('index');
    Route::get('/create', [PatientWardAllocationController::class, 'create'])->name('create');
    Route::post('/', [PatientWardAllocationController::class, 'store'])->name('store');
    Route::get('/{allocation}', [PatientWardAllocationController::class, 'show'])->name('show');
    Route::get('/{allocation}/edit', [PatientWardAllocationController::class, 'edit'])->name('edit');
    Route::put('/{allocation}', [PatientWardAllocationController::class, 'update'])->name('update');
    Route::put('/{allocation}/discharge', [PatientWardAllocationController::class, 'discharge'])->name('discharge');
    Route::post('/{allocation}/generate-pdf', [PatientWardAllocationController::class, 'generatePdf'])->name('generate-pdf');
});

// Patient Monitoring
Route::post('/patient-monitoring/{allocation}', [PatientMonitoringController::class, 'store'])->name('patient-monitoring.store');
Route::put('/patient-monitoring/{monitoring}', [PatientMonitoringController::class, 'update'])->name('patient-monitoring.update');
Route::delete('/patient-monitoring/{monitoring}', [PatientMonitoringController::class, 'destroy'])->name('patient-monitoring.destroy');

// Medication Management
Route::post('/medications/{allocation}', [MedicationController::class, 'store'])->name('medications.store');
Route::put('/medications/{medication}', [MedicationController::class, 'update'])->name('medications.update');
Route::delete('/medications/{medication}', [MedicationController::class, 'destroy'])->name('medications.destroy');

// Patient Monitoring Details
Route::get('/patient-monitoring/{monitoring}/details', [PatientMonitoringController::class, 'getDetails'])->name('patient-monitoring.details');