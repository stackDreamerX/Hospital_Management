<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function pharmacy() {
        return view('admin.pharmacy');
    }
}
