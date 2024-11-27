<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WardController extends Controller
{
    public function ward() {
        return view('admin.ward');
    }
}
