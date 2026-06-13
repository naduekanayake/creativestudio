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
}