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
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\EmailSharingController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;

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

Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp');

Route::get('/email-sharing', [EmailSharingController::class, 'index'])->name('email-sharing');

Route::resource('reminders', ReminderController::class)->except(['show']);
Route::patch('/reminders/{reminder}/status', [ReminderController::class, 'updateStatus'])->name('reminders.update-status');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');