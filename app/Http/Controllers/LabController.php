<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabController extends Controller
{
    public function lab() {
        return view('admin.lab');
    }
}
