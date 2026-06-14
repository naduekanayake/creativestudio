@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-gray-400 text-sm mt-0.5">Welcome back, John! Here's what's happening.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="flex items-center gap-2 bg-dark-800 border border-dark-700 rounded-lg px-3 py-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-gray-300 text-sm">{{ now()->format('d M Y') }}</span>
        </div>
        
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-6 gap-4 mb-6">

    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ number_format($stats['total_clients']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Clients</p>
        <p class="text-green-400 text-xs mt-1">+12% from last month</p>
    </div>

    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['active_jobs'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Active Jobs</p>
        <p class="text-green-400 text-xs mt-1">+12.5% this month</p>
    </div>

    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">Rs. {{ number_format($stats['total_revenue']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Revenue</p>
        <p class="text-green-400 text-xs mt-1">+18.6% vs Apr 2024</p>
    </div>

    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">Rs. {{ number_format($stats['pending_payments']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Pending Payments</p>
        <p class="text-red-400 text-xs mt-1">-8.2% vs last month</p>
    </div>

    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-teal-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['completed_jobs'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Completed Jobs</p>
        <p class="text-green-400 text-xs mt-1">+14.3% vs Apr 2024</p>
    </div>

    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="w-9 h-9 bg-red-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['overdue_jobs'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Overdue Jobs</p>
        <p class="text-red-400 text-xs mt-1">14.0% of total</p>
    </div>

</div>

{{-- Charts Row --}}
<div class="grid grid-cols-3 gap-4 mb-4">

    {{-- Revenue Chart --}}
    <div class="col-span-2 bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-semibold">Revenue Overview</h3>
            <select class="bg-dark-700 text-gray-300 text-xs border border-dark-600 rounded-lg px-2 py-1">
                <option>This Month</option>
                <option>Last Month</option>
                <option>This Year</option>
            </select>
        </div>
        <div id="revenueChart"></div>
    </div>

    {{-- Job Pipeline --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-semibold">Job Pipeline</h3>
        </div>
        <div id="pipelineChart"></div>
        <div class="mt-3 space-y-2">
            @foreach([
                ['label' => 'Enquiry', 'count' => 14, 'color' => 'bg-purple-400'],
                ['label' => 'Booked', 'count' => 12, 'color' => 'bg-blue-400'],
                ['label' => 'Shooting', 'count' => 10, 'color' => 'bg-yellow-400'],
                ['label' => 'Editing', 'count' => 15, 'color' => 'bg-orange-400'],
                ['label' => 'Review', 'count' => 8, 'color' => 'bg-pink-400'],
                ['label' => 'Delivered', 'count' => 27, 'color' => 'bg-green-400'],
            ] as $item)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full {{ $item['color'] }}"></div>
                    <span class="text-gray-400 text-xs">{{ $item['label'] }}</span>
                </div>
                <span class="text-white text-xs font-medium">{{ $item['count'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Bottom Row --}}
<div class="grid grid-cols-3 gap-4">

    {{-- Recent Jobs --}}
    <div class="col-span-2 bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-semibold">Recent Jobs</h3>
            <a href="#" class="text-primary text-xs hover:underline">View All</a>
        </div>
        <table class="w-full">
            <thead>
                <tr class="text-gray-500 text-xs border-b border-dark-700">
                    <th class="text-left pb-2">Client</th>
                    <th class="text-left pb-2">Type</th>
                    <th class="text-left pb-2">Date</th>
                    <th class="text-left pb-2">Status</th>
                    <th class="text-left pb-2">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700">
                @foreach([
                    ['name' => 'Kasun Perera', 'type' => 'Wedding Photography', 'date' => '22 Jun 2024', 'status' => 'In Progress', 'status_color' => 'bg-blue-500/20 text-blue-400', 'amount' => '450,000'],
                    ['name' => 'Supun & Hasini', 'type' => 'Wedding Photography', 'date' => '18 Jun 2024', 'status' => 'Booked', 'status_color' => 'bg-purple-500/20 text-purple-400', 'amount' => '180,000'],
                    ['name' => "Dilshan's Studio", 'type' => 'Commercial Shoot', 'date' => '15 Jun 2024', 'status' => 'Completed', 'status_color' => 'bg-green-500/20 text-green-400', 'amount' => '95,000'],
                    ['name' => 'Randika & Erandi', 'type' => 'Wedding Photography', 'date' => '10 Jun 2024', 'status' => 'Overdue', 'status_color' => 'bg-red-500/20 text-red-400', 'amount' => '200,000'],
                    ['name' => 'Melani Perera', 'type' => 'Portrait Session', 'date' => '02 Jun 2024', 'status' => 'Completed', 'status_color' => 'bg-green-500/20 text-green-400', 'amount' => '65,000'],
                ] as $job)
                <tr class="text-sm">
                    <td class="py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-primary/20 rounded-full flex items-center justify-center">
                                <span class="text-primary text-xs font-bold">{{ substr($job['name'], 0, 1) }}</span>
                            </div>
                            <span class="text-white text-xs">{{ $job['name'] }}</span>
                        </div>
                    </td>
                    <td class="py-2.5 text-gray-400 text-xs">{{ $job['type'] }}</td>
                    <td class="py-2.5 text-gray-400 text-xs">{{ $job['date'] }}</td>
                    <td class="py-2.5">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $job['status_color'] }}">
                            {{ $job['status'] }}
                        </span>
                    </td>
                    <td class="py-2.5 text-white text-xs font-medium">Rs. {{ $job['amount'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Upcoming Events --}}
    <div class="bg-dark-800 border border-dark-700 rounded-xl p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-semibold">Upcoming Events</h3>
            <a href="#" class="text-primary text-xs hover:underline">View All</a>
        </div>
        <div class="space-y-3">
            @foreach([
                ['date' => '08', 'month' => 'JUN', 'title' => 'Product Shoot', 'sub' => "Dilshan's Studio", 'type' => 'Shoot', 'color' => 'bg-purple-500'],
                ['date' => '09', 'month' => 'JUN', 'title' => 'Client Review', 'sub' => 'Supun & Hasini', 'type' => 'Meeting', 'color' => 'bg-blue-500'],
                ['date' => '10', 'month' => 'JUN', 'title' => 'Wedding Shoot', 'sub' => 'Vimal & Shalini', 'type' => 'Shoot', 'color' => 'bg-purple-500'],
                ['date' => '12', 'month' => 'JUN', 'title' => 'Album Delivery', 'sub' => 'Kasun & Nadeesha', 'type' => 'Delivery', 'color' => 'bg-green-500'],
            ] as $event)
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-dark-700 rounded-lg flex flex-col items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-bold leading-none">{{ $event['date'] }}</span>
                    <span class="text-gray-500 text-xs">{{ $event['month'] }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-white text-xs font-medium">{{ $event['title'] }}</p>
                    <p class="text-gray-500 text-xs">{{ $event['sub'] }}</p>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $event['color'] }}/20 text-white">
                    {{ $event['type'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ApexCharts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Revenue Chart
    new ApexCharts(document.getElementById('revenueChart'), {
        series: [{ name: 'Revenue (Rs.)', data: [500000, 800000, 600000, 1200000, 900000, 1500000, 2650000] }],
        chart: { type: 'area', height: 200, toolbar: { show: false }, background: 'transparent' },
        colors: ['#7C3AED'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0, stops: [0, 100] } },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: { categories: ['Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], labels: { style: { colors: '#6B7280', fontSize: '11px' } } },
        yaxis: { labels: { style: { colors: '#6B7280', fontSize: '11px' }, formatter: (v) => 'Rs. ' + (v/1000000).toFixed(1) + 'M' } },
        grid: { borderColor: '#252840' },
        tooltip: { theme: 'dark' },
    }).render();

    // Pipeline Donut
    new ApexCharts(document.getElementById('pipelineChart'), {
        series: [14, 12, 10, 15, 8, 27],
        chart: { type: 'donut', height: 160, background: 'transparent' },
        colors: ['#A78BFA', '#60A5FA', '#FBBF24', '#FB923C', '#F472B6', '#34D399'],
        labels: ['Enquiry', 'Booked', 'Shooting', 'Editing', 'Review', 'Delivered'],
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'Total', color: '#9CA3AF', fontSize: '11px', formatter: () => '86' } } } } },
        tooltip: { theme: 'dark' },
    }).render();

});
</script>

@endsection