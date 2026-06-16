<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Job;

class EmailSharingController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('name')->get();
        $invoices = Invoice::with('client')->latest()->get();
        $quotations = Quotation::with('client')->latest()->get();
        $jobs = Job::with('client')
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->latest()->get();

        return view('email-sharing.index', compact('clients', 'invoices', 'quotations', 'jobs'));
    }
}