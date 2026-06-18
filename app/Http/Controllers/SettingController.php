<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAll();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'studio_name'    => 'nullable|string|max:255',
            'studio_tagline' => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:50',
            'phone2'         => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:100',
            'website'        => 'nullable|string|max:255',
            'currency'       => 'nullable|string|max:10',
            'logo_url'       => 'nullable|string|max:500',
            'bank_name'      => 'nullable|string|max:255',
            'bank_account'   => 'nullable|string|max:100',
            'bank_branch'    => 'nullable|string|max:255',
            'invoice_prefix' => 'nullable|string|max:20',
            'invoice_footer' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log('updated', 'Settings', null, 'System Settings',
            'Studio settings updated', 'settings', 'orange');

        return back()->with('success', 'Settings updated successfully!');
    }
}