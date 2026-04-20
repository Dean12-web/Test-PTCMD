<?php

use App\Http\Controllers\admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\front\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApplicationController::class, 'index']);

Route::get('/application', [AdminApplicationController::class, 'index'])->name('admin');

Route::post('/application-store', [AdminApplicationController::class, 'store'])->name('application-store');

Route::get('/application-view', [AdminApplicationController::class, 'view'])->name('application-view');

Route::post('/application-approve/{id}', [AdminApplicationController::class, 'approve'])->name('application-approve');

Route::post('/application-reject/{id}', [AdminApplicationController::class, 'reject'])->name('application-reject');