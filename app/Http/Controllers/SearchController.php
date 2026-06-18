<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Job;
use App\Models\Invoice;
use App\Models\Quotation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Clients
        $clients = Client::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('phone', 'like', "%{$q}%")
            ->take(4)->get();
        foreach ($clients as $client) {
            $results[] = [
                'type'  => 'Client',
                'title' => $client->name,
                'sub'   => $client->phone ?? $client->email ?? '',
                'url'   => route('clients.show', $client),
                'color' => 'blue',
            ];
        }

        // Jobs
        $jobs = Job::where('title', 'like', "%{$q}%")
            ->orWhere('job_number', 'like', "%{$q}%")
            ->take(4)->get();
        foreach ($jobs as $job) {
            $results[] = [
                'type'  => 'Job',
                'title' => $job->title,
                'sub'   => $job->job_number,
                'url'   => route('jobs.show', $job),
                'color' => 'pink',
            ];
        }

        // Invoices
        $invoices = Invoice::with('client')->where('invoice_number', 'like', "%{$q}%")->take(4)->get();
        foreach ($invoices as $invoice) {
            $results[] = [
                'type'  => 'Invoice',
                'title' => $invoice->invoice_number,
                'sub'   => $invoice->client->name ?? '',
                'url'   => route('invoices.show', $invoice),
                'color' => 'purple',
            ];
        }

        // Quotations
        $quotations = Quotation::with('client')->where('quotation_number', 'like', "%{$q}%")->take(4)->get();
        foreach ($quotations as $quotation) {
            $results[] = [
                'type'  => 'Quotation',
                'title' => $quotation->quotation_number,
                'sub'   => $quotation->client->name ?? '',
                'url'   => route('quotations.show', $quotation),
                'color' => 'orange',
            ];
        }

        return response()->json(['results' => $results]);
    }
}