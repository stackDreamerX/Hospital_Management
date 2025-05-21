@extends('layout')
@section('content')
<div class="container my-5">
    <h1 class="text-center mb-3" data-aos="fade-up">Request an Appointment</h1>
    <p class="text-center lead mb-5" data-aos="fade-up" data-aos-delay="100">Please complete the form below to request an appointment with one of our specialists</p>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <form class="appointment-form card shadow-lg p-4" data-aos="fade-up" data-aos-delay="200">
                <!-- Form Progress Bar -->
                <div class="form-progress mb-4">
                    <div class="progress-step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-text">Personal Info</div>
                    </div>
                    <div class="progress-connector"></div>
                    <div class="progress-step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-text">Appointment Details</div>
                    </div>
                    <div class="progress-connector"></div>
                    <div class="progress-step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-text">Medical Info</div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="appointment-step active" id="step-1">
                    <h3 class="mb-4">Personal Information</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required-field">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label required-field">Date of Birth</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="appointment-step" id="step-2">
                    <h3 class="mb-4">Appointment Details</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required-field">Department</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-hospital"></i></span>
                                <select class="form-select" required>
                                    <option value="">Select department</option>
                                    <option>Cardiology</option>
                                    <option>Neurology</option>
                                    <option>Orthopedics</option>
                                    <option>Pediatrics</option>
                                    <option>General Medicine</option>
                                    <option>Dermatology</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Doctor (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-md"></i></span>
                                <select class="form-select">
                                    <option value="">Select doctor</option>
                                    <option>Dr. John Smith</option>
                                    <option>Dr. Jane Doe</option>
                                    <option>Dr. Alice Green</option>
                                    <option>Dr. Brian Carter</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Preferred Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Preferred Time</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                <select class="form-select" required>
                                    <option value="">Select time</option>
                                    <option>Morning (8:00 AM - 12:00 PM)</option>
                                    <option>Afternoon (12:00 PM - 4:00 PM)</option>
                                    <option>Evening (4:00 PM - 8:00 PM)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-primary prev-step"><i class="fas fa-arrow-left me-2"></i> Previous</button>
                            <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right ms-2"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="appointment-step" id="step-3">
                    <h3 class="mb-4">Medical Information</h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label required-field">Reason for Visit</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-notes-medical"></i></span>
                                <textarea class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Any previous medical conditions?</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                <textarea class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Current medications</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-pills"></i></span>
                                <textarea class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required-field">Insurance Provider</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-file-medical"></i></span>
                                <select class="form-select" required>
                                    <option value="">Select insurance</option>
                                    <option>Medicare</option>
                                    <option>Blue Cross</option>
                                    <option>Aetna</option>
                                    <option>United Healthcare</option>
                                    <option>Cigna</option>
                                    <option>Self-Pay</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Insurance ID (if applicable)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-primary prev-step"><i class="fas fa-arrow-left me-2"></i> Previous</button>
                            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-2"></i> Submit Request</button>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" required>
                        <label class="form-check-label">
                            I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                    <p class="small mt-3 text-muted">
                        Note: This is a request form. Our staff will contact you to confirm your appointment.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row mt-5 g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 info-card">
                <div class="card-body">
                    <i class="fas fa-phone-alt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Call Directly</h5>
                    <p class="card-text">For urgent appointments, please call our scheduling office</p>
                    <a href="tel:037.864.9957" class="btn btn-outline-primary">037.864.9957</a>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100 info-card">
                <div class="card-body">
                    <i class="fas fa-calendar-check fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">MyChart</h5>
                    <p class="card-text">Existing patients can schedule through our patient portal</p>
                    <a href="#" class="btn btn-outline-primary">Log in to MyChart</a>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100 info-card">
                <div class="card-body">
                    <i class="fas fa-laptop-medical fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Virtual Visits</h5>
                    <p class="card-text">Schedule a video appointment with your doctor</p>
                    <a href="#" class="btn btn-outline-primary">Request Virtual Visit</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-progress {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e0e6ed;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    
    .progress-step.active .step-number {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 0 0 5px rgba(0, 146, 216, 0.2);
    }
    
    .progress-connector {
        flex-grow: 1;
        height: 3px;
        background-color: #e0e6ed;
        margin: 0 10px;
        align-self: center;
        position: relative;
        top: -20px;
        z-index: 0;
    }
    
    .step-text {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
    }
    
    .progress-step.active .step-text {
        color: var(--primary-color);
    }
    
    .appointment-step {
        display: none;
    }
    
    .appointment-step.active {
        display: block;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form navigation
        const nextButtons = document.querySelectorAll('.next-step');
        const prevButtons = document.querySelectorAll('.prev-step');
        const steps = document.querySelectorAll('.appointment-step');
        const progressSteps = document.querySelectorAll('.progress-step');
        
        nextButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Find current active step
                const currentStep = document.querySelector('.appointment-step.active');
                const currentIndex = Array.from(steps).indexOf(currentStep);
                
                if (currentIndex < steps.length - 1) {
                    // Hide current step
                    currentStep.classList.remove('active');
                    
                    // Show next step
                    steps[currentIndex + 1].classList.add('active');
                    
                    // Update progress indicators
                    progressSteps[currentIndex].classList.add('completed');
                    progressSteps[currentIndex + 1].classList.add('active');
                }
            });
        });
        
        prevButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Find current active step
                const currentStep = document.querySelector('.appointment-step.active');
                const currentIndex = Array.from(steps).indexOf(currentStep);
                
                if (currentIndex > 0) {
                    // Hide current step
                    currentStep.classList.remove('active');
                    
                    // Show previous step
                    steps[currentIndex - 1].classList.add('active');
                    
                    // Update progress indicators
                    progressSteps[currentIndex].classList.remove('active');
                    progressSteps[currentIndex - 1].classList.remove('completed');
                    progressSteps[currentIndex - 1].classList.add('active');
                }
            });
        });
    });
</script>
@endsection
