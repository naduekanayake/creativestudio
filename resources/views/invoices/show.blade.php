@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')

{{-- Header --}}
<div class="no-print flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('invoices.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoice->invoice_number }}</h1>
            <p class="text-gray-400 text-sm">Invoice Details</p>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $invoice->type_color }}">{{ $invoice->type }}</span>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $invoice->status_color }}">{{ $invoice->status }}</span>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="window.print()"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print / PDF
        </button>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="no-print bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Status Update Bar --}}
<div class="no-print rounded-xl p-4 mb-4 flex items-center justify-between"
     :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="flex items-center gap-3">
        <span class="text-gray-400 text-sm">Update Status:</span>
        <div class="flex items-center gap-2">
            @foreach(['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'] as $statusOption)
            <form method="POST" action="{{ route('invoices.update-status', $invoice) }}" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $statusOption }}">
                <button type="submit"
                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $invoice->status === $statusOption ? 'bg-primary text-white' : '' }}"
                        :class="'{{ $invoice->status === $statusOption ? '1' : '0' }}' === '0' ? (dark ? 'bg-dark-700 text-gray-400 hover:bg-dark-600' : 'bg-gray-100 text-gray-500 hover:bg-gray-200') : ''">
                    {{ $statusOption }}
                </button>
            </form>
            @endforeach
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('invoices.edit', $invoice) }}"
           class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5"
           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <div class="text-right">
            <p class="text-gray-400 text-xs">Created on</p>
            <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoice->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</div>

{{-- Payment Summary Bar --}}
<div class="no-print grid grid-cols-3 gap-4 mb-4">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">TOTAL AMOUNT</p>
        <p class="text-xl font-bold text-primary">Rs. {{ number_format($invoice->total_amount, 2) }}</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">AMOUNT PAID</p>
        <p class="text-xl font-bold text-green-400">Rs. {{ number_format($invoice->paid_amount, 2) }}</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <p class="text-gray-400 text-xs mb-1">BALANCE DUE</p>
        <p class="text-xl font-bold {{ $invoice->balance_due > 0 ? 'text-orange-400' : 'text-green-400' }}">
            Rs. {{ number_format($invoice->balance_due, 2) }}
        </p>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $invoice->payment_status_color }}">{{ $invoice->payment_status }}</span>
    </div>
</div>

{{-- Main Invoice Document --}}
<div class="rounded-xl p-8 max-w-4xl mx-auto" id="printArea"
     :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

    {{-- Document Header --}}
    <div class="flex items-center justify-between mb-6 pb-6" style="border-bottom:2px solid #7C3AED">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">CREATIVE STUDIO</h2>
                <p class="text-gray-400 text-xs tracking-wide">PHOTOGRAPHY & FILMS</p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-3xl font-bold text-primary">{{ strtoupper($invoice->type) }} INVOICE</h2>
            <p class="text-gray-400 text-sm mt-1">{{ $invoice->invoice_number }}</p>
        </div>
    </div>

    {{-- From / To --}}
    <div class="grid grid-cols-2 gap-8 mb-6">
        <div>
            <p class="text-primary text-xs font-semibold mb-2">FROM</p>
            <p class="font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Creative Studio</p>
            <p class="text-gray-400 text-sm">No. 45, Park Road,</p>
            <p class="text-gray-400 text-sm">Colombo 05, Sri Lanka</p>
            <p class="text-gray-400 text-sm mt-2">077 123 4567</p>
            <p class="text-gray-400 text-sm">info@creativestudio.lk</p>
        </div>
        <div>
            <p class="text-primary text-xs font-semibold mb-2">BILL TO</p>
            <p class="font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoice->client->name }}</p>
            @if($invoice->client->address)
            <p class="text-gray-400 text-sm">{{ $invoice->client->address }}</p>
            @endif
            @if($invoice->client->city)
            <p class="text-gray-400 text-sm">{{ $invoice->client->city }}</p>
            @endif
            @if($invoice->client->phone)
            <p class="text-gray-400 text-sm mt-2">{{ $invoice->client->phone }}</p>
            @endif
            @if($invoice->client->email)
            <p class="text-gray-400 text-sm">{{ $invoice->client->email }}</p>
            @endif
        </div>
    </div>

    {{-- Meta Info Bar --}}
    <div class="grid grid-cols-4 gap-4 p-4 rounded-lg mb-6" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
        <div>
            <p class="text-gray-400 text-xs">ISSUE DATE</p>
            <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoice->issue_date->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs">DUE DATE</p>
            <p class="font-medium text-sm {{ $invoice->balance_due > 0 && $invoice->due_date->isPast() ? 'text-red-400' : '' }}"
               :class="'{{ $invoice->balance_due > 0 && $invoice->due_date->isPast() ? '1' : '0' }}' === '0' ? (dark ? 'text-white' : 'text-gray-900') : ''">
                {{ $invoice->due_date->format('d M Y') }}
            </p>
        </div>
        <div>
            <p class="text-gray-400 text-xs">PAYMENT TERMS</p>
            <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoice->payment_terms ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs">PROJECT / EVENT</p>
            <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $invoice->project_event ?? '-' }}</p>
        </div>
    </div>

    {{-- Items Table --}}
    <table class="w-full mb-6">
        <thead>
            <tr class="bg-primary text-white text-xs">
                <th class="text-left px-3 py-2 rounded-tl-lg">#</th>
                <th class="text-left px-3 py-2">ITEM / SERVICE</th>
                <th class="text-left px-3 py-2">DESCRIPTION</th>
                <th class="text-right px-3 py-2">QTY</th>
                <th class="text-right px-3 py-2">UNIT PRICE</th>
                <th class="text-right px-3 py-2 rounded-tr-lg">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                <td class="px-3 py-3 text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $index + 1 }}</td>
                <td class="px-3 py-3 text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $item->item_name }}</td>
                <td class="px-3 py-3 text-sm text-gray-400">{{ $item->description ?? '-' }}</td>
                <td class="px-3 py-3 text-sm text-right" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $item->qty }}</td>
                <td class="px-3 py-3 text-sm text-right" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ number_format($item->unit_price, 2) }}</td>
                <td class="px-3 py-3 text-sm text-right font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="flex justify-end mb-6">
        <div class="w-72 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">SUB TOTAL</span>
                <span :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($invoice->sub_total, 2) }}</span>
            </div>
            @if($invoice->discount_percent > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">DISCOUNT ({{ $invoice->discount_percent }}%)</span>
                <span class="text-red-400">- Rs. {{ number_format($invoice->sub_total * ($invoice->discount_percent / 100), 2) }}</span>
            </div>
            @endif
            @if($invoice->vat_percent > 0)
            @php
                $afterDiscount = $invoice->sub_total - ($invoice->sub_total * ($invoice->discount_percent / 100));
                $vatAmount = $afterDiscount * ($invoice->vat_percent / 100);
            @endphp
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">VAT ({{ $invoice->vat_percent }}%)</span>
                <span :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($vatAmount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between pt-2 text-lg font-bold" style="border-top:2px solid #7C3AED">
                <span class="text-primary">TOTAL AMOUNT</span>
                <span class="text-primary">Rs. {{ number_format($invoice->total_amount, 2) }}</span>
            </div>
            @if($invoice->paid_amount > 0)
            <div class="flex justify-between text-sm">
                <span class="text-green-400">AMOUNT PAID</span>
                <span class="text-green-400">Rs. {{ number_format($invoice->paid_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold">
                <span class="text-orange-400">BALANCE DUE</span>
                <span class="text-orange-400">Rs. {{ number_format($invoice->balance_due, 2) }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Terms --}}
    @if($invoice->terms)
    <div class="pt-6" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        <p class="font-semibold text-sm mb-2" :class="dark ? 'text-white' : 'text-gray-900'">Terms & Notes</p>
        <p class="text-gray-400 text-xs whitespace-pre-line leading-relaxed">{{ $invoice->terms }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="mt-8 text-center">
        <p class="text-2xl text-primary mb-1" style="font-family: cursive;">Thank you!</p>
        <p class="text-gray-400 text-xs">We look forward to capturing your special moments.</p>
        <div class="mt-4">
            <p class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">John Perera</p>
            <p class="text-gray-400 text-xs">Creative Director</p>
        </div>
    </div>

</div>

<style>
@media print {
    aside, header, .no-print { display: none !important; }
    #printArea {
        background: white !important;
        color: black !important;
        box-shadow: none !important;
        border: none !important;
    }
    #printArea * { color: black !important; }
}
</style>

@endsection