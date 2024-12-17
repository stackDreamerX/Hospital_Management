<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index() {
        return view('patient.dashboard');
    }

    public function profile() {
        echo 1;
    }

    public function settings() {
        echo 1;
    }

    public function logout() {
        echo 1;
    }

    public function patients() {
        echo 1;
    }

    public function patients_details() {
        echo 1;
    }
   
}
