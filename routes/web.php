<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\DeliverableController;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('clients', ClientController::class);
Route::resource('packages', PackageController::class);
Route::resource('quotations', QuotationController::class);
Route::patch('/quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');
Route::resource('invoices', InvoiceController::class);
Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');
Route::resource('payments', PaymentController::class);
Route::resource('jobs', JobController::class);
Route::patch('/jobs/{job}/status', [JobController::class, 'updateStatus'])->name('jobs.update-status');
Route::resource('deliverables', DeliverableController::class);
Route::patch('/deliverables/{deliverable}/status', [DeliverableController::class, 'updateStatus'])->name('deliverables.update-status');