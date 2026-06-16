@extends('layouts.app')

@section('title', 'Payment ' . $payment->payment_number)

@section('content')

{{-- Header --}}
<div class="no-print flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('payments.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $payment->payment_number }}</h1>
            <p class="text-gray-400 text-sm">Payment Details</p>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $payment->status_color }}">{{ $payment->status }}</span>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $payment->method_color }}">{{ $payment->method }}</span>
    </div>
    <div class="flex items-center gap-2">
        <button onclick="window.print()"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Receipt
        </button>
        <a href="{{ route('payments.edit', $payment) }}"
           class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('payments.destroy', $payment) }}"
              onsubmit="return confirm('Delete this payment?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2 bg-red-500/20 text-red-400 hover:bg-red-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete
            </button>
        </form>
    </div>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="no-print bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Payment Receipt --}}
<div class="rounded-xl p-8 max-w-2xl mx-auto" id="printArea"
     :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

    {{-- Receipt Header --}}
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
            <h2 class="text-2xl font-bold text-primary">RECEIPT</h2>
            <p class="text-gray-400 text-sm mt-1">{{ $payment->payment_number }}</p>
        </div>
    </div>

    {{-- Client Info --}}
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
            <p class="text-primary text-xs font-semibold mb-2">RECEIVED FROM</p>
            <p class="font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $payment->client->name }}</p>
            @if($payment->client->address)
            <p class="text-gray-400 text-sm">{{ $payment->client->address }}</p>
            @endif
            @if($payment->client->city)
            <p class="text-gray-400 text-sm">{{ $payment->client->city }}</p>
            @endif
            @if($payment->client->phone)
            <p class="text-gray-400 text-sm mt-2">{{ $payment->client->phone }}</p>
            @endif
            @if($payment->client->email)
            <p class="text-gray-400 text-sm">{{ $payment->client->email }}</p>
            @endif
        </div>
    </div>

    {{-- Payment Details --}}
    <div class="rounded-lg p-4 mb-6" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-1">PAYMENT DATE</p>
                <p class="font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $payment->payment_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">PAYMENT METHOD</p>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $payment->method_color }}">{{ $payment->method }}</span>
            </div>
            @if($payment->invoice)
            <div>
                <p class="text-gray-400 text-xs mb-1">LINKED INVOICE</p>
                <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-primary text-sm hover:underline">
                    {{ $payment->invoice->invoice_number }}
                </a>
            </div>
            @endif
            <div>
                <p class="text-gray-400 text-xs mb-1">STATUS</p>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $payment->status_color }}">{{ $payment->status }}</span>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">RECORDED BY</p>
                <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">John Perera</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">RECORDED ON</p>
                <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $payment->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>

    {{-- Amount --}}
    <div class="text-center py-8 mb-6" style="border-top:1px solid #7C3AED;border-bottom:1px solid #7C3AED">
        <p class="text-gray-400 text-xs mb-2 tracking-widest">AMOUNT RECEIVED</p>
        <p class="text-5xl font-bold text-primary">Rs. {{ number_format($payment->amount, 2) }}</p>
    </div>

    {{-- Notes --}}
    @if($payment->notes)
    <div class="mb-6 p-4 rounded-lg" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
        <p class="font-semibold text-sm mb-1" :class="dark ? 'text-white' : 'text-gray-900'">Notes</p>
        <p class="text-gray-400 text-sm">{{ $payment->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="text-center mt-8">
        <p class="text-2xl text-primary mb-1" style="font-family: cursive;">Thank you!</p>
        <p class="text-gray-400 text-xs">Thank you for your payment. We appreciate your business.</p>
        <div class="mt-6 pt-4" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
            <p class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">John Perera</p>
            <p class="text-gray-400 text-xs">Creative Director, Creative Studio</p>
        </div>
    </div>

</div>

<style>
@media print {
    aside, header, .no-print { display: none !important; }
    #printArea {
        background: white !important;
        color: black !important;
        border: none !important;
        max-width: 100% !important;
        padding: 20px !important;
    }
    #printArea * { color: black !important; }
}
</style>

@endsection