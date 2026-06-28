@extends('layouts.app')

@section('title', 'Reports')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Reports & Analytics</h1>
        <p class="text-gray-400 text-sm mt-0.5">Business performance overview</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.financial') }}"
           class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            Financial Report
        </a>
        <button onclick="window.print()"
                class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / PDF
        </button>
    </div>
</div>

{{-- Print Header (only visible in print) --}}
<div class="hidden print:block mb-6 text-center">
    <h1 class="text-2xl font-bold text-gray-900">{{ \App\Models\Setting::get('studio_name', 'Creative Studio') }}</h1>
    <p class="text-gray-600 text-sm">{{ \App\Models\Setting::get('studio_tagline', 'Photography & Films') }}</p>
    <p class="text-gray-500 text-xs mt-2">Business Performance Report · Generated {{ now()->format('d M Y, h:i A') }}</p>
    <hr class="mt-3" style="border-color:#7C3AED;border-width:1px"/>
</div>

{{-- Overview Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-green-400">Rs. {{ number_format($stats['total_revenue']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Revenue</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-orange-400">Rs. {{ number_format($stats['pending_payments']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Pending Payments</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total_clients'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Clients</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total_invoices'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Invoices</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-pink-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total_jobs'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Jobs</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-teal-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['completed_jobs'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Completed Jobs</p>
    </div>
</div>

{{-- Charts Row 1 --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

    {{-- Monthly Revenue Chart --}}
    <div class="col-span-2 rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Monthly Revenue</h3>
        <div id="revenueChart"></div>
    </div>

    {{-- Invoice Status --}}
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Invoice Status</h3>
        <div id="invoiceChart"></div>
        <div class="space-y-2 mt-4">
            <div class="flex justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-400">Paid</span>
                </div>
                <span :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoiceStats['paid'] }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                    <span class="text-gray-400">Partial</span>
                </div>
                <span :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoiceStats['partial'] }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-gray-400">Unpaid</span>
                </div>
                <span :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoiceStats['unpaid'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 2 --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

    {{-- Jobs by Status --}}
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Jobs by Status</h3>
        <div id="jobStatusChart"></div>
    </div>

    {{-- Jobs by Type --}}
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Jobs by Type</h3>
        <div id="jobTypeChart"></div>
    </div>

    {{-- Payment Methods --}}
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Payment Methods</h3>
        <div id="paymentMethodChart"></div>
    </div>
</div>

{{-- Leads by Source --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Leads by Source</h3>
        <div id="leadSourceChart"></div>
    </div>

    {{-- Lead Source Breakdown (list) --}}
    <div class="col-span-2 rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Marketing Channels — Where Clients Come From</h3>
        <div class="space-y-3">
            @forelse($leadsBySource as $index => $lead)
            @php
                $maxLeads = collect($leadsBySource)->max('count') ?: 1;
                $width = round(($lead['count'] / $maxLeads) * 100);
                $barColors = ['bg-blue-500','bg-pink-500','bg-purple-500','bg-orange-500','bg-teal-500','bg-red-500','bg-yellow-500','bg-cyan-500','bg-green-500','bg-indigo-500','bg-rose-500','bg-gray-500'];
                $barColor = $barColors[$index % count($barColors)];
            @endphp
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $lead['name'] }}</span>
                        <span class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $lead['count'] }} {{ $lead['count'] == 1 ? 'client' : 'clients' }}</span>
                    </div>
                    <div class="h-1.5 rounded-full" :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                        <div class="h-1.5 rounded-full {{ $barColor }}" style="width: {{ $width }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm">No lead source data yet. Add clients with a lead source to see analytics.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    {{-- Top Clients --}}
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Top Clients by Revenue</h3>
        <div class="space-y-3">
            @forelse($topClients as $index => $client)
            @php
                $maxRevenue = $topClients->max('payments_sum_amount') ?: 1;
                $width = round((($client->payments_sum_amount ?? 0) / $maxRevenue) * 100);
            @endphp
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-xs font-bold">{{ substr($client->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $client->name }}</span>
                        <span class="text-sm text-green-400 font-medium">Rs. {{ number_format($client->payments_sum_amount ?? 0) }}</span>
                    </div>
                    <div class="h-1.5 rounded-full" :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                        <div class="h-1.5 rounded-full bg-primary" style="width: {{ $width }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm">No payment data yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Recent Payments</h3>
        <div class="space-y-3">
            @forelse($recentPayments as $payment)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $payment->client->name }}</p>
                        <p class="text-xs text-gray-400">{{ $payment->payment_date->format('d M Y') }} · {{ $payment->method }}</p>
                    </div>
                </div>
                <p class="text-sm font-medium text-green-400">Rs. {{ number_format($payment->amount) }}</p>
            </div>
            @empty
            <p class="text-gray-500 text-sm">No payments yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ApexCharts CDN --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@php
    $monthlyLabelsJson = json_encode($monthlyLabels);
    $monthlyRevenueJson = json_encode($monthlyRevenue);
    $invoiceStatsJson = json_encode([$invoiceStats['paid'], $invoiceStats['partial'], $invoiceStats['unpaid']]);
    $jobStatusLabels = json_encode(array_column($jobsByStatus, 'name'));
    $jobStatusData = json_encode(array_column($jobsByStatus, 'count'));
    $jobTypeLabels = json_encode(array_column($jobsByType, 'name'));
    $jobTypeData = json_encode(array_column($jobsByType, 'count'));
    $payMethodLabels = json_encode(array_column($paymentMethods, 'name'));
    $payMethodData = json_encode(array_column($paymentMethods, 'total'));
    $leadSourceLabels = json_encode(array_column($leadsBySource, 'name'));
    $leadSourceData = json_encode(array_column($leadsBySource, 'count'));
@endphp

<script>
// Monthly Revenue Chart
new ApexCharts(document.getElementById('revenueChart'), {
    chart: { type: 'area', height: 220, background: 'transparent', toolbar: { show: false } },
    series: [{ name: 'Revenue (Rs.)', data: {{ $monthlyRevenueJson }} }],
    xaxis: { categories: {{ $monthlyLabelsJson }}, labels: { style: { colors: '#9ca3af', fontSize: '11px' } } },
    yaxis: { labels: { style: { colors: '#9ca3af', fontSize: '11px' }, formatter: (v) => 'Rs. ' + v.toLocaleString() } },
    colors: ['#7C3AED'],
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
    stroke: { curve: 'smooth', width: 2 },
    grid: { borderColor: '#252840' },
    tooltip: { theme: 'dark', y: { formatter: (v) => 'Rs. ' + v.toLocaleString() } },
    theme: { mode: 'dark' },
}).render();

// Invoice Status Donut
new ApexCharts(document.getElementById('invoiceChart'), {
    chart: { type: 'donut', height: 160, background: 'transparent' },
    series: {{ $invoiceStatsJson }},
    labels: ['Paid', 'Partial', 'Unpaid'],
    colors: ['#22c55e', '#f97316', '#ef4444'],
    legend: { show: false },
    dataLabels: { enabled: false },
    plotOptions: { pie: { donut: { size: '70%' } } },
    theme: { mode: 'dark' },
    tooltip: { theme: 'dark' },
}).render();

// Jobs by Status
new ApexCharts(document.getElementById('jobStatusChart'), {
    chart: { type: 'bar', height: 200, background: 'transparent', toolbar: { show: false } },
    series: [{ name: 'Jobs', data: {{ $jobStatusData }} }],
    xaxis: { categories: {{ $jobStatusLabels }}, labels: { style: { colors: '#9ca3af', fontSize: '10px' } } },
    yaxis: { labels: { style: { colors: '#9ca3af' } } },
    colors: ['#7C3AED'],
    grid: { borderColor: '#252840' },
    plotOptions: { bar: { borderRadius: 4 } },
    theme: { mode: 'dark' },
    tooltip: { theme: 'dark' },
}).render();

// Jobs by Type
new ApexCharts(document.getElementById('jobTypeChart'), {
    chart: { type: 'donut', height: 200, background: 'transparent' },
    series: {{ $jobTypeData }},
    labels: {{ $jobTypeLabels }},
    colors: ['#ec4899', '#a855f7', '#3b82f6', '#f97316', '#14b8a6', '#6b7280'],
    legend: { position: 'bottom', labels: { colors: '#9ca3af' }, fontSize: '11px' },
    dataLabels: { enabled: false },
    theme: { mode: 'dark' },
    tooltip: { theme: 'dark' },
}).render();

// Payment Methods
new ApexCharts(document.getElementById('paymentMethodChart'), {
    chart: { type: 'bar', height: 200, background: 'transparent', toolbar: { show: false } },
    series: [{ name: 'Amount (Rs.)', data: {{ $payMethodData }} }],
    xaxis: { categories: {{ $payMethodLabels }}, labels: { style: { colors: '#9ca3af', fontSize: '11px' } } },
    yaxis: { labels: { style: { colors: '#9ca3af' }, formatter: (v) => 'Rs. ' + v.toLocaleString() } },
    colors: ['#22c55e'],
    grid: { borderColor: '#252840' },
    plotOptions: { bar: { borderRadius: 4, horizontal: true } },
    theme: { mode: 'dark' },
    tooltip: { theme: 'dark', y: { formatter: (v) => 'Rs. ' + v.toLocaleString() } },
}).render();

// Leads by Source Donut
new ApexCharts(document.getElementById('leadSourceChart'), {
    chart: { type: 'donut', height: 240, background: 'transparent' },
    series: {{ $leadSourceData }},
    labels: {{ $leadSourceLabels }},
    colors: ['#3b82f6', '#ec4899', '#a855f7', '#f97316', '#14b8a6', '#ef4444', '#eab308', '#06b6d4', '#22c55e', '#6366f1', '#f43f5e', '#6b7280'],
    legend: { position: 'bottom', labels: { colors: '#9ca3af' }, fontSize: '11px' },
    dataLabels: { enabled: true, style: { fontSize: '10px' } },
    theme: { mode: 'dark' },
    tooltip: { theme: 'dark' },
}).render();
</script>

<style>
@media print {
    body { background: white !important; }
    aside, header, .print\:hidden { display: none !important; }
    main { padding: 0 !important; background: white !important; }
    .rounded-xl { border: 1px solid #e5e7eb !important; background: white !important; box-shadow: none !important; page-break-inside: avoid; }
    * { color: #111827 !important; }
    @page { margin: 1.5cm; }
}
</style>

@endsection