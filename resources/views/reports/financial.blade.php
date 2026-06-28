@extends('layouts.app')

@section('title', 'Financial Report')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Financial Report</h1>
        <p class="text-gray-400 text-sm mt-0.5">Revenue, invoices & payment analytics</p>
    </div>
    <a href="{{ route('reports.index') }}"
       class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
       :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
        ← Overview
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">Total Invoiced</p>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($invoiceStats['total_invoiced']) }}</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">Total Collected</p>
        <p class="text-2xl font-bold text-green-400">Rs. {{ number_format($invoiceStats['total_collected']) }}</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">Total Pending</p>
        <p class="text-2xl font-bold text-orange-400">Rs. {{ number_format($invoiceStats['total_pending']) }}</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">Total Invoices</p>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoiceStats['total_invoices'] }}</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">Paid Invoices</p>
        <p class="text-2xl font-bold text-green-400">{{ $invoiceStats['paid_invoices'] }}</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">Overdue Invoices</p>
        <p class="text-2xl font-bold text-red-400">{{ $invoiceStats['overdue_invoices'] }}</p>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="col-span-2 rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Revenue vs Invoiced (Last 6 Months)</h3>
        <div id="financialChart"></div>
    </div>
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Payment Methods</h3>
        @forelse($paymentMethods as $method)
        <div class="mb-3">
            <div class="flex justify-between text-xs mb-1">
                <span class="text-gray-400">{{ $method->method }}</span>
                <span :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($method->total) }}</span>
            </div>
            <div class="h-1.5 rounded-full" :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                @php
                    $maxMethod = $paymentMethods->max('total') ?: 1;
                    $w = ($method->total / $maxMethod) * 100;
                @endphp
                <div class="h-1.5 rounded-full bg-primary" style="width: {{ $w }}%"></div>
            </div>
            <p class="text-gray-500 text-xs mt-0.5">{{ $method->count }} payments</p>
        </div>
        @empty
        <p class="text-gray-500 text-sm">No payment data yet.</p>
        @endforelse
    </div>
</div>

{{-- Payments Table --}}
<div class="rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="p-4" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
        <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">Payment History</h3>
    </div>
    <div class="overflow-x-auto"><table class="w-full min-w-[640px]">
        <thead>
            <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <th class="text-left px-4 py-3">PAYMENT #</th>
                <th class="text-left px-4 py-3">CLIENT</th>
                <th class="text-left px-4 py-3">INVOICE</th>
                <th class="text-left px-4 py-3">DATE</th>
                <th class="text-left px-4 py-3">METHOD</th>
                <th class="text-right px-4 py-3">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                <td class="px-4 py-3 text-sm text-primary">{{ $payment->payment_number }}</td>
                <td class="px-4 py-3 text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $payment->client->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-400">{{ $payment->invoice ? $payment->invoice->invoice_number : '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-400">{{ $payment->payment_date->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $payment->method_color }}">{{ $payment->method }}</span>
                </td>
                <td class="px-4 py-3 text-sm font-medium text-green-400 text-right">Rs. {{ number_format($payment->amount) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">No payments yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table></div>
    @if($payments->hasPages())
    <div class="px-4 py-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        {{ $payments->links() }}
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@php
    $labelsJson = json_encode($monthlyLabels);
    $revenueJson = json_encode($monthlyData['revenue']);
    $invoicedJson = json_encode($monthlyData['invoiced']);
@endphp

<script>
new ApexCharts(document.getElementById('financialChart'), {
    chart: { type: 'bar', height: 250, background: 'transparent', toolbar: { show: false } },
    series: [
        { name: 'Collected (Rs.)', data: {{ $revenueJson }} },
        { name: 'Invoiced (Rs.)', data: {{ $invoicedJson }} },
    ],
    xaxis: { categories: {{ $labelsJson }}, labels: { style: { colors: '#9ca3af', fontSize: '11px' } } },
    yaxis: { labels: { style: { colors: '#9ca3af' }, formatter: (v) => 'Rs. ' + v.toLocaleString() } },
    colors: ['#22c55e', '#7C3AED'],
    grid: { borderColor: '#252840' },
    plotOptions: { bar: { borderRadius: 4, columnWidth: '60%' } },
    theme: { mode: 'dark' },
    tooltip: { theme: 'dark', y: { formatter: (v) => 'Rs. ' + v.toLocaleString() } },
    legend: { labels: { colors: '#9ca3af' } },
}).render();
</script>

@endsection