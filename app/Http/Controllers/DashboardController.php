<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients' => 128,
            'active_jobs' => 28,
            'total_revenue' => 2650000,
            'pending_payments' => 875000,
            'completed_jobs' => 32,
            'overdue_jobs' => 12,
        ];

        return view('dashboard', compact('stats'));
    }

    public function updateWidgets(Request $request)
    {
        $widgets = $request->input('widgets', []);

        $allowed = ['stats', 'recent_jobs', 'quick_stats', 'recent_activity'];
        $clean = array_values(array_intersect($allowed, $widgets));

        $user = $request->user();
        $user->update(['dashboard_widgets' => $clean]);

        return back()->with('success', 'Dashboard layout saved!');
    }
}