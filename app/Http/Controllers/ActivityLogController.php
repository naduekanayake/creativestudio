<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::latest()->paginate(20);
        $stats = [
            'total'   => ActivityLog::count(),
            'today'   => ActivityLog::whereDate('created_at', today())->count(),
            'created' => ActivityLog::where('action', 'created')->count(),
            'deleted' => ActivityLog::where('action', 'deleted')->count(),
        ];
        return view('activity-log.index', compact('logs', 'stats'));
    }
}