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
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;

Route::get('/', fn() => redirect()->route('login'));

// ===== PUBLIC SHARE ROUTES (no auth — customers view via share_token) =====
Route::get('/invoice/view/{token}', [InvoiceController::class, 'publicView'])->name('invoices.public');
Route::get('/invoice/view/{token}/pdf', [InvoiceController::class, 'publicPdf'])->name('invoices.public-pdf');
Route::get('/quotation/view/{token}', [QuotationController::class, 'publicView'])->name('quotations.public');
Route::get('/quotation/view/{token}/pdf', [QuotationController::class, 'publicPdf'])->name('quotations.public-pdf');

Route::middleware('auth')->group(function () {

    // ===== ALL ROLES (super_admin, admin, staff) =====
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/dashboard/widgets', [DashboardController::class, 'updateWidgets'])->name('dashboard.widgets');

    Route::resource('clients', ClientController::class);

    Route::resource('jobs', JobController::class);
    Route::patch('/jobs/{job}/status', [JobController::class, 'updateStatus'])->name('jobs.update-status');

    Route::resource('deliverables', DeliverableController::class);
    Route::patch('/deliverables/{deliverable}/status', [DeliverableController::class, 'updateStatus'])->name('deliverables.update-status');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    Route::get('/reminders/due', [ReminderController::class, 'due'])->name('reminders.due');
    Route::resource('reminders', ReminderController::class)->except(['show']);
    Route::patch('/reminders/{reminder}/status', [ReminderController::class, 'updateStatus'])->name('reminders.update-status');

    Route::get('/whatsapp', [WhatsAppController::class, 'index'])->name('whatsapp');
    Route::get('/email-sharing', [EmailSharingController::class, 'index'])->name('email-sharing');

    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Profile — all roles (own profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', fn() => redirect()->route('dashboard'))->name('profile.destroy');


    // ===== SUPER ADMIN + ADMIN ONLY (financial & management) =====
    Route::middleware('role:super_admin,admin')->group(function () {

        Route::resource('packages', PackageController::class);

        Route::resource('quotations', QuotationController::class);
	Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'downloadPdf'])->name('quotations.pdf');
       Route::post('/quotations/{quotation}/email', [QuotationController::class, 'sendEmail'])->name('quotations.email');
        Route::patch('/quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');

        Route::resource('invoices', InvoiceController::class);
	Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');		
       Route::post('/invoices/{invoice}/email', [InvoiceController::class, 'sendEmail'])->name('invoices.email');
        Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.update-status');

        Route::resource('payments', PaymentController::class);

        Route::resource('expenses', ExpenseController::class);

        Route::resource('contracts', ContractController::class);
        Route::patch('/contracts/{contract}/status', [ContractController::class, 'updateStatus'])->name('contracts.update-status');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');

        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');

        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });


    // ===== SUPER ADMIN ONLY (system settings) =====
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

});

require __DIR__.'/auth.php';
