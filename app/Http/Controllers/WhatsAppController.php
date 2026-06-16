<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Job;

class WhatsAppController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('name')->get();
        $invoices = Invoice::with('client')
            ->whereIn('payment_status', ['Unpaid', 'Partial'])
            ->latest()->get();
        $quotations = Quotation::with('client')
            ->whereIn('status', ['Draft', 'Sent'])
            ->latest()->get();
        $jobs = Job::with('client')
            ->whereNotIn('status', ['Completed', 'Cancelled'])
            ->latest()->get();

        return view('whatsapp.index', compact('clients', 'invoices', 'quotations', 'jobs'));
    }
}