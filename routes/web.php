<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front.index');
});

Route::get('/admin', function () {
    return view('admin.index');
})->name('admin');



