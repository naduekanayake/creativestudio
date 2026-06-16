<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Job;
use App\Models\Client;
use App\Models\Quotation;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Overview Stats
        $stats = [
            'total_revenue'    => Payment::where('status', 'Completed')->sum('amount'),
            'total_invoices'   => Invoice::count(),
            'total_clients'    => Client::count(),
            'total_jobs'       => Job::count(),
            'pending_payments' => Invoice::whereIn('payment_status', ['Unpaid', 'Partial'])
                ->selectRaw('SUM(total_amount - paid_amount) as pending')
                ->value('pending') ?? 0,
            'completed_jobs'   => Job::where('status', 'Completed')->count(),
        ];

        // Monthly Revenue - Last 6 months
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyRevenue[] = (float) Payment::where('status', 'Completed')
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
        }

        // Payment Methods breakdown
        $paymentMethods = Payment::where('status', 'Completed')
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->get()
            ->map(fn($p) => ['name' => $p->method, 'total' => (float) $p->total])
            ->values()->toArray();

        // Jobs by Status
        $jobsByStatus = Job::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(fn($j) => ['name' => $j->status, 'count' => $j->count])
            ->values()->toArray();

        // Jobs by Type
        $jobsByType = Job::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->map(fn($j) => ['name' => $j->type, 'count' => $j->count])
            ->values()->toArray();

        // Top Clients by revenue
        $topClients = Client::withSum(['payments' => function ($q) {
            $q->where('status', 'Completed');
        }], 'amount')
            ->orderByDesc('payments_sum_amount')
            ->take(5)
            ->get();

        // Recent Payments
        $recentPayments = Payment::with('client')
            ->where('status', 'Completed')
            ->latest('payment_date')
            ->take(5)
            ->get();

        // Invoice Status breakdown
        $invoiceStats = [
            'paid'    => Invoice::where('payment_status', 'Paid')->count(),
            'partial' => Invoice::where('payment_status', 'Partial')->count(),
            'unpaid'  => Invoice::where('payment_status', 'Unpaid')->count(),
        ];

        return view('reports.index', compact(
            'stats', 'monthlyRevenue', 'monthlyLabels',
            'paymentMethods', 'jobsByStatus', 'jobsByType',
            'topClients', 'recentPayments', 'invoiceStats'
        ));
    }

    public function financial()
    {
        // Monthly Revenue vs Invoiced - Last 6 months
        $monthlyLabels = [];
        $monthlyData = ['revenue' => [], 'invoiced' => []];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyData['revenue'][] = (float) Payment::where('status', 'Completed')
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
            $monthlyData['invoiced'][] = (float) Invoice::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
        }

        // Payment method totals
        $paymentMethods = Payment::where('status', 'Completed')
            ->selectRaw('method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('method')
            ->get();

        // Invoice stats
        $invoiceStats = [
            'total_invoiced'   => Invoice::sum('total_amount'),
            'total_collected'  => Payment::where('status', 'Completed')->sum('amount'),
            'total_pending'    => Invoice::whereIn('payment_status', ['Unpaid', 'Partial'])
                ->selectRaw('SUM(total_amount - paid_amount) as pending')
                ->value('pending') ?? 0,
            'total_invoices'   => Invoice::count(),
            'paid_invoices'    => Invoice::where('payment_status', 'Paid')->count(),
            'overdue_invoices' => Invoice::where('payment_status', '!=', 'Paid')
                ->where('due_date', '<', today())
                ->count(),
        ];

        // All payments list
        $payments = Payment::with('client', 'invoice')
            ->where('status', 'Completed')
            ->latest('payment_date')
            ->paginate(10);

        return view('reports.financial', compact(
            'monthlyLabels', 'monthlyData',
            'paymentMethods', 'invoiceStats', 'payments'
        ));
    }
}