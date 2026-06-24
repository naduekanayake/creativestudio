@extends('layouts.app')

@section('title', 'Quotation ' . $quotation->quotation_number)

@section('content')

@php
    $studioLogo = \App\Models\Setting::logoUrl();
    $studioName = \App\Models\Setting::get('studio_name', 'Creative Studio');
    $studioTagline = \App\Models\Setting::get('studio_tagline', 'Photography & Films');
    $studioAddress = \App\Models\Setting::get('address', 'No. 45, Park Road');
    $studioCity = \App\Models\Setting::get('city', 'Colombo 05');
    $studioPhone = \App\Models\Setting::get('phone', '077 123 4567');
    $studioEmail = \App\Models\Setting::get('email', 'info@creativestudio.lk');
@endphp

{{-- Header --}}
<div class="no-print flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('quotations.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $quotation->quotation_number }}</h1>
            <p class="text-gray-400 text-sm">Quotation Details</p>
        </div>
        <span class="px-2 py-0.5 rounded-full text-xs {{ $quotation->status_color }}">
            {{ $quotation->status }}
        </span>
    </div>
    <div class="flex items-center gap-2">
        @php
            $studioNameWa = \App\Models\Setting::get('studio_name', 'Creative Studio');
            $waPhone = preg_replace('/[^0-9]/', '', $quotation->client->phone ?? '');
            if (substr($waPhone, 0, 1) === '0') { $waPhone = '94' . substr($waPhone, 1); }
            $waMessage = "Hello {$quotation->client->name},\n\nYour quotation *{$quotation->quotation_number}* from {$studioNameWa} is ready.\n\n*Total: Rs. " . number_format($quotation->total_amount, 2) . "*\nValid until: " . $quotation->valid_until->format('d M Y') . "\n\nView & download here:\n{$quotation->share_url}\n\nThank you!";
            $waLink = 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($waMessage);
        @endphp

        <a href="{{ $waLink }}" target="_blank"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Send WhatsApp
        </a>

        <button onclick="copyShareLink()" type="button"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
                :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            Copy Link
        </button>

        <a href="{{ route('quotations.pdf', $quotation) }}"
           class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            PDF
        </a>
	<form method="POST" action="{{ route('quotations.email', $quotation) }}" class="inline"
              onsubmit="return confirm('Send this quotation to {{ $quotation->client->email ?? 'the client' }}?')">
            @csrf
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Send Email
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
@if(session('error'))
<div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- Status Update Bar --}}
<div class="no-print rounded-xl p-4 mb-4 flex items-center justify-between"
     :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="flex items-center gap-3">
        <span class="text-gray-400 text-sm">Update Status:</span>
        <div class="flex items-center gap-2">
            @foreach(['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired'] as $statusOption)
            <form method="POST" action="{{ route('quotations.update-status', $quotation) }}" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $statusOption }}">
                <button type="submit"
                        class="px-3 py-1 rounded-full text-xs font-medium transition-colors
                        {{ $quotation->status === $statusOption ? 'bg-primary text-white' : '' }}"
                        :class="'{{ $quotation->status === $statusOption ? "1" : "0" }}' === '0'
                            ? (dark ? 'bg-dark-700 text-gray-400 hover:bg-dark-600' : 'bg-gray-100 text-gray-500 hover:bg-gray-200')
                            : ''">
                    {{ $statusOption }}
                </button>
            </form>
            @endforeach
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('quotations.edit', $quotation) }}"
           class="text-sm font-medium px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5"
           :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <div class="text-right">
            <p class="text-gray-400 text-xs">Created on</p>
            <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $quotation->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</div>

{{-- Main Quotation Document --}}
<div class="rounded-xl p-8 max-w-4xl mx-auto" id="printArea"
     :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

    {{-- Document Header --}}
    <div class="flex items-center justify-between mb-6 pb-6" style="border-bottom:2px solid #7C3AED">
        <div class="flex items-center gap-3">
            @if($studioLogo)
            <div class="w-24 h-24 rounded-xl flex items-center justify-center overflow-hidden bg-white">
                <img src="{{ $studioLogo }}" class="w-full h-full object-contain"/>
            </div>
            @else
            <div class="w-24 h-24 bg-primary rounded-xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            @endif
            <div>
                <h2 class="text-xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ strtoupper($studioName) }}</h2>
                <p class="text-gray-400 text-xs tracking-wide">{{ strtoupper($studioTagline) }}</p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-3xl font-bold text-primary">QUOTATION</h2>
            <p class="text-gray-400 text-sm mt-1">{{ $quotation->quotation_number }}</p>
        </div>
    </div>

    {{-- From / To --}}
    <div class="grid grid-cols-2 gap-8 mb-6">
        <div>
            <p class="text-primary text-xs font-semibold mb-2">FROM</p>
            <p class="font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $studioName }}</p>
            @if($studioAddress)
            <p class="text-gray-400 text-sm">{{ $studioAddress }}</p>
            @endif
            @if($studioCity)
            <p class="text-gray-400 text-sm">{{ $studioCity }}</p>
            @endif
            @if($studioPhone)
            <p class="text-gray-400 text-sm mt-2">{{ $studioPhone }}</p>
            @endif
            @if($studioEmail)
            <p class="text-gray-400 text-sm">{{ $studioEmail }}</p>
            @endif
        </div>
        <div>
            <p class="text-primary text-xs font-semibold mb-2">TO</p>
            <p class="font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $quotation->client->name }}</p>
            @if($quotation->client->address)
            <p class="text-gray-400 text-sm">{{ $quotation->client->address }}</p>
            @endif
            @if($quotation->client->city)
            <p class="text-gray-400 text-sm">{{ $quotation->client->city }}</p>
            @endif
            @if($quotation->client->phone)
            <p class="text-gray-400 text-sm mt-2">{{ $quotation->client->phone }}</p>
            @endif
            @if($quotation->client->email)
            <p class="text-gray-400 text-sm">{{ $quotation->client->email }}</p>
            @endif
        </div>
    </div>

    {{-- Meta Info Bar --}}
    <div class="grid grid-cols-4 gap-4 p-4 rounded-lg mb-6" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
        <div>
            <p class="text-gray-400 text-xs">ISSUE DATE</p>
            <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $quotation->issue_date->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-gray-400 text-xs">VALID UNTIL</p>
            <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $quotation->valid_until->format('d M Y') }}</p>
        </div>
        <div class="col-span-2">
            <p class="text-gray-400 text-xs">PROJECT / EVENT</p>
            <p class="font-medium text-sm" :class="dark ? 'text-white' : 'text-gray-900'">{{ $quotation->project_event ?? '-' }}</p>
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
            @foreach($quotation->items as $index => $item)
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
                <span :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($quotation->sub_total, 2) }}</span>
            </div>
            @if($quotation->discount_percent > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">DISCOUNT ({{ $quotation->discount_percent }}%)</span>
                <span class="text-red-400">- Rs. {{ number_format($quotation->sub_total * ($quotation->discount_percent / 100), 2) }}</span>
            </div>
            @endif
            @if($quotation->vat_percent > 0)
            @php
                $afterDiscount = $quotation->sub_total - ($quotation->sub_total * ($quotation->discount_percent / 100));
                $vatAmount = $afterDiscount * ($quotation->vat_percent / 100);
            @endphp
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">VAT ({{ $quotation->vat_percent }}%)</span>
                <span :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($vatAmount, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between pt-2 text-lg font-bold" style="border-top:2px solid #7C3AED">
                <span class="text-primary">TOTAL AMOUNT</span>
                <span class="text-primary">Rs. {{ number_format($quotation->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Terms & Payment --}}
    <div class="grid grid-cols-2 gap-8 pt-6" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        @if($quotation->terms)
        <div>
            <p class="font-semibold text-sm mb-2" :class="dark ? 'text-white' : 'text-gray-900'">Terms & Conditions</p>
            <p class="text-gray-400 text-xs whitespace-pre-line leading-relaxed">{{ $quotation->terms }}</p>
        </div>
        @endif
        <div>
            @if($quotation->payment_terms)
            <p class="font-semibold text-sm mb-2" :class="dark ? 'text-white' : 'text-gray-900'">Payment Terms</p>
            <p class="text-gray-400 text-xs">{{ $quotation->payment_terms }}</p>
            @endif

            <div class="mt-6 text-right">
                <p class="font-script text-2xl text-primary mb-1" style="font-family: cursive;">Thank you!</p>
                <p class="text-gray-400 text-xs">We look forward to capturing your special moments.</p>
            </div>
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
    #printArea * {
        color: black !important;
    }
}
</style>
<script>
function copyShareLink() {
    const link = "{{ $quotation->share_url }}";
    navigator.clipboard.writeText(link).then(() => {
        alert('Quotation link copied!\n\n' + link);
    });
}
</script>

@endsection
