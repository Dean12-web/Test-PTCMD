<?php

use App\Http\Controllers\admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\front\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApplicationController::class, 'index']);

Route::get('/application', [AdminApplicationController::class, 'index'])->name('admin');

Route::post('/application-store', [AdminApplicationController::class, 'store'])->name('application-store');
