@extends('layouts.app')

@section('title', 'Payments')

@section('content')

<div x-data="paymentSearch()">

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Payments</h1>
        <p class="text-gray-400 text-sm mt-0.5">Track and manage all payments received</p>
    </div>
    <a href="{{ route('payments.create') }}"
       class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Record Payment
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Payments</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['completed'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Completed</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['pending'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Pending</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($stats['total_amount']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Received</p>
    </div>
</div>

{{-- Payments Table --}}
<div class="rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
        <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">All Payments</h3>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" x-model="search" placeholder="Search payments..."
                   class="text-sm rounded-lg pl-9 pr-3 py-1.5 w-56 focus:outline-none focus:border-primary"
                   :style="dark ? 'background:#252840;color:#d1d5db;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
        </div>
    </div>

    <div class="overflow-x-auto">
<table class="w-full min-w-[640px]">
        <thead>
            <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <th class="text-left px-4 py-3">PAYMENT #</th>
                <th class="text-left px-4 py-3">CLIENT</th>
                <th class="text-left px-4 py-3">INVOICE</th>
                <th class="text-left px-4 py-3">DATE</th>
                <th class="text-left px-4 py-3">METHOD</th>
                <th class="text-left px-4 py-3">AMOUNT</th>
                <th class="text-left px-4 py-3">STATUS</th>
                <th class="text-left px-4 py-3">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr class="searchable-row"
                data-search="{{ strtolower($payment->payment_number . ' ' . $payment->client->name . ' ' . ($payment->invoice ? $payment->invoice->invoice_number : '') . ' ' . $payment->method . ' ' . $payment->status) }}"
                x-show="matches('{{ strtolower($payment->payment_number . ' ' . $payment->client->name . ' ' . ($payment->invoice ? $payment->invoice->invoice_number : '') . ' ' . $payment->method . ' ' . $payment->status) }}')"
                :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $payment->payment_number }}</p>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $payment->client->name }}</p>
                </td>
                <td class="px-4 py-3 text-sm text-gray-400">
                    {{ $payment->invoice ? $payment->invoice->invoice_number : '-' }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-400">
                    {{ $payment->payment_date->format('d M Y') }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $payment->method_color }}">
                        {{ $payment->method }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <p class="text-sm font-medium text-green-400">Rs. {{ number_format($payment->amount, 2) }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $payment->status_color }}">
                        {{ $payment->status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('payments.show', $payment) }}"
                           class="text-gray-400 hover:text-primary transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('payments.destroy', $payment) }}"
                              onsubmit="return confirm('Delete this payment?')">
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
                    No payments yet. Record your first payment!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{-- No search results --}}
    <div x-show="search.length > 0 && visibleCount() === 0" class="px-4 py-8 text-center text-gray-500 text-sm">
        No payments match "<span x-text="search"></span>"
    </div>

    @if($payments->hasPages())
    <div class="px-4 py-3" x-show="search.length === 0" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        {{ $payments->links() }}
    </div>
    @endif
</div>

</div>

<script>
function paymentSearch() {
    return {
        search: '',
        matches(text) {
            if (this.search.length === 0) return true;
            return text.includes(this.search.toLowerCase());
        },
        visibleCount() {
            if (this.search.length === 0) return 1;
            const rows = document.querySelectorAll('.searchable-row');
            let count = 0;
            rows.forEach(row => {
                if (row.dataset.search.includes(this.search.toLowerCase())) count++;
            });
            return count;
        }
    }
}
</script>

@endsection