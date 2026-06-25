<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Job;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // ===== Core counts =====
        $totalClients = Client::count();
        $totalJobs = Job::count();
        $totalRevenue = Payment::where('status', 'Completed')->sum('amount');
        $pendingInvoices = Invoice::whereIn('payment_status', ['Unpaid', 'Partial'])->count();

        // ===== This month income vs expense =====
        $monthIncome = Payment::where('status', 'Completed')
            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthExpense = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthProfit = $monthIncome - $monthExpense;

        // ===== Pending payments total (balance due) =====
        $pendingPaymentsTotal = Invoice::whereIn('payment_status', ['Unpaid', 'Partial'])
            ->selectRaw('SUM(total_amount - paid_amount) as due')
            ->value('due') ?? 0;

        // ===== Overdue invoices =====
        $overdueInvoices = Invoice::where('payment_status', '!=', 'Paid')
            ->where('due_date', '<', $now->toDateString())
            ->count();

        // ===== Quotation conversion rate =====
        $totalQuotations = Quotation::count();
        $acceptedQuotations = Quotation::where('status', 'Accepted')->count();
        $conversionRate = $totalQuotations > 0
            ? round(($acceptedQuotations / $totalQuotations) * 100)
            : 0;

        // ===== Upcoming events (next 30 days) =====
        $upcomingEvents = Job::with('client')
            ->whereNotNull('event_date')
            ->whereBetween('event_date', [$now->toDateString(), $now->copy()->addDays(30)->toDateString()])
            ->orderBy('event_date')
            ->take(5)
            ->get();

        // ===== Income vs Expense — last 6 months (for chart) =====
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $mStart = $month->copy()->startOfMonth();
            $mEnd = $month->copy()->endOfMonth();

            $chartLabels[] = $month->format('M');
            $chartIncome[] = (float) Payment::where('status', 'Completed')
                ->whereBetween('payment_date', [$mStart, $mEnd])
                ->sum('amount');
            $chartExpense[] = (float) Expense::whereBetween('expense_date', [$mStart, $mEnd])
                ->sum('amount');
        }

        $analytics = [
            'total_clients'          => $totalClients,
            'total_jobs'             => $totalJobs,
            'total_revenue'          => $totalRevenue,
            'pending_invoices'       => $pendingInvoices,
            'month_income'           => $monthIncome,
            'month_expense'          => $monthExpense,
            'month_profit'           => $monthProfit,
            'pending_payments_total' => $pendingPaymentsTotal,
            'overdue_invoices'       => $overdueInvoices,
            'conversion_rate'        => $conversionRate,
            'accepted_quotations'    => $acceptedQuotations,
            'total_quotations'       => $totalQuotations,
            'upcoming_events'        => $upcomingEvents,
            'chart_labels'           => $chartLabels,
            'chart_income'           => $chartIncome,
            'chart_expense'          => $chartExpense,
        ];

        return view('dashboard', compact('analytics'));
    }

    public function updateWidgets(Request $request)
    {
        $widgets = $request->input('widgets', []);

        $allowed = ['stats', 'analytics', 'recent_jobs', 'quick_stats', 'recent_activity'];
        $clean = array_values(array_intersect($allowed, $widgets));

        $user = $request->user();
        $user->update(['dashboard_widgets' => $clean]);

        return back()->with('success', 'Dashboard layout saved!');
   }
    public function downloadBackup()
    {
        $backupDir = '/home/wwwaxora/backups';

        if (!is_dir($backupDir)) {
            return back()->with('error', 'No backups found yet.');
        }

        $files = glob($backupDir . '/studio_backup_*.zip');
        if (empty($files)) {
            return back()->with('error', 'No backup files found. Create one first.');
        }

    
        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));
        $latest = $files[0];

        return response()->download($latest, basename($latest));
    }

  
    public function runBackup()
    {
        $script = '/home/wwwaxora/backup-studio.sh';

        if (!file_exists($script)) {
            return back()->with('error', 'Backup script not found on server.');
        }

        exec('/bin/bash ' . escapeshellarg($script) . ' 2>&1', $output, $code);

        if ($code !== 0) {
            return back()->with('error', 'Backup failed. Please try again or contact support.');
        }

        return back()->with('success', 'New backup created successfully! Click "Download Backup" to save it.');
    }
}
