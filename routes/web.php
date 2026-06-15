<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\QuotationController;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('clients', ClientController::class);
Route::resource('packages', PackageController::class);
Route::resource('quotations', QuotationController::class)->except(['edit', 'update']);