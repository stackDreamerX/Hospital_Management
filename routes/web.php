<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});


Route::get('/trang-chu', function () {
    return view('layout');
});