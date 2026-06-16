@extends('layouts.app')

@section('title', 'Invoices')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Invoices</h1>
        <p class="text-gray-400 text-sm mt-0.5">Generate and manage invoices & payments</p>
    </div>
    <a href="{{ route('invoices.create') }}"
       class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create Invoice
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Invoices</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['paid'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Fully Paid</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-red-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['unpaid'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Unpaid</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($stats['total_due']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Due</p>
    </div>
</div>

{{-- Invoices Table --}}
<div class="rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
        <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">All Invoices</h3>
        <input type="text" placeholder="Search invoices..."
               class="text-sm rounded-lg px-3 py-1.5 w-48 focus:outline-none"
               :style="dark ? 'background:#252840;color:#d1d5db;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
    </div>

    <table class="w-full">
        <thead>
            <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <th class="text-left px-4 py-3">INVOICE</th>
                <th class="text-left px-4 py-3">TYPE</th>
                <th class="text-left px-4 py-3">CLIENT</th>
                <th class="text-left px-4 py-3">DUE DATE</th>
                <th class="text-left px-4 py-3">AMOUNT</th>
                <th class="text-left px-4 py-3">PAYMENT</th>
                <th class="text-left px-4 py-3">STATUS</th>
                <th class="text-left px-4 py-3">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoice->invoice_number }}</p>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $invoice->type_color }}">
                        {{ $invoice->type }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $invoice->client->name }}</p>
                </td>
                <td class="px-4 py-3 text-gray-400 text-sm">{{ $invoice->due_date->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($invoice->total_amount, 2) }}</p>
                    @if($invoice->balance_due > 0)
                    <p class="text-orange-400 text-xs">Due: Rs. {{ number_format($invoice->balance_due, 2) }}</p>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $invoice->payment_status_color }}">
                        {{ $invoice->payment_status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $invoice->status_color }}">
                        {{ $invoice->status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('invoices.show', $invoice) }}"
                           class="text-gray-400 hover:text-primary transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
                              onsubmit="return confirm('Delete this invoice?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                    No invoices yet. Create your first invoice!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($invoices->hasPages())
    <div class="px-4 py-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

@endsection