<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @php
        $studioLogo = \App\Models\Setting::logoUrl();
        $studioName = \App\Models\Setting::get('studio_name', 'Creative Studio');
        $studioTagline = \App\Models\Setting::get('studio_tagline', 'Photography & Films');
        $studioAddress = \App\Models\Setting::get('address', '');
        $studioCity = \App\Models\Setting::get('city', '');
        $studioPhone = \App\Models\Setting::get('phone', '');
        $studioEmail = \App\Models\Setting::get('email', '');
    @endphp
</head>
<body style="background:#f3f4f6; padding:20px;">

    <div class="max-w-3xl mx-auto mb-4 flex justify-end">
        <a href="{{ route('quotations.public-pdf', $quotation->share_token) }}"
           class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Download PDF
        </a>
    </div>

    <div class="max-w-3xl mx-auto bg-white rounded-xl p-8 shadow-sm">

        {{-- Header --}}
        <div class="flex items-center justify-between pb-6 mb-6" style="border-bottom:2px solid #7C3AED">
            <div class="flex items-center gap-3">
                @if($studioLogo)
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-white flex items-center justify-center">
                    <img src="{{ $studioLogo }}" class="w-full h-full object-contain"/>
                </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ strtoupper($studioName) }}</h2>
                    <p class="text-gray-500 text-xs tracking-wide">{{ strtoupper($studioTagline) }}</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-bold" style="color:#7C3AED">QUOTATION</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $quotation->quotation_number }}</p>
            </div>
        </div>

        {{-- From / To --}}
        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <p class="text-xs font-semibold mb-2" style="color:#7C3AED">FROM</p>
                <p class="font-bold text-gray-900">{{ $studioName }}</p>
                @if($studioAddress)<p class="text-gray-500 text-sm">{{ $studioAddress }}</p>@endif
                @if($studioCity)<p class="text-gray-500 text-sm">{{ $studioCity }}</p>@endif
                @if($studioPhone)<p class="text-gray-500 text-sm mt-2">{{ $studioPhone }}</p>@endif
                @if($studioEmail)<p class="text-gray-500 text-sm">{{ $studioEmail }}</p>@endif
            </div>
            <div>
                <p class="text-xs font-semibold mb-2" style="color:#7C3AED">TO</p>
                <p class="font-bold text-gray-900">{{ $quotation->client->name }}</p>
                @if($quotation->client->address)<p class="text-gray-500 text-sm">{{ $quotation->client->address }}</p>@endif
                @if($quotation->client->city)<p class="text-gray-500 text-sm">{{ $quotation->client->city }}</p>@endif
                @if($quotation->client->phone)<p class="text-gray-500 text-sm mt-2">{{ $quotation->client->phone }}</p>@endif
                @if($quotation->client->email)<p class="text-gray-500 text-sm">{{ $quotation->client->email }}</p>@endif
            </div>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-3 gap-4 p-4 rounded-lg mb-6" style="background:#f9fafb">
            <div>
                <p class="text-gray-500 text-xs">ISSUE DATE</p>
                <p class="font-medium text-sm text-gray-900">{{ $quotation->issue_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">VALID UNTIL</p>
                <p class="font-medium text-sm text-gray-900">{{ $quotation->valid_until->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs">PROJECT / EVENT</p>
                <p class="font-medium text-sm text-gray-900">{{ $quotation->project_event ?? '-' }}</p>
            </div>
        </div>

        {{-- Items --}}
        <table class="w-full mb-6">
            <thead>
                <tr class="text-white text-xs" style="background:#7C3AED">
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
                <tr style="border-bottom:1px solid #f3f4f6">
                    <td class="px-3 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                    <td class="px-3 py-3 text-sm font-medium text-gray-900">{{ $item->item_name }}</td>
                    <td class="px-3 py-3 text-sm text-gray-500">{{ $item->description ?? '-' }}</td>
                    <td class="px-3 py-3 text-sm text-right text-gray-700">{{ $item->qty }}</td>
                    <td class="px-3 py-3 text-sm text-right text-gray-700">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="px-3 py-3 text-sm text-right font-medium text-gray-900">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="flex justify-end mb-6">
            <div class="w-72 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">SUB TOTAL</span>
                    <span class="text-gray-900">Rs. {{ number_format($quotation->sub_total, 2) }}</span>
                </div>
                @if($quotation->discount_percent > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">DISCOUNT ({{ $quotation->discount_percent }}%)</span>
                    <span style="color:#dc2626">- Rs. {{ number_format($quotation->sub_total * ($quotation->discount_percent / 100), 2) }}</span>
                </div>
                @endif
                @if($quotation->vat_percent > 0)
                @php
                    $afterDiscount = $quotation->sub_total - ($quotation->sub_total * ($quotation->discount_percent / 100));
                    $vatAmount = $afterDiscount * ($quotation->vat_percent / 100);
                @endphp
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">VAT ({{ $quotation->vat_percent }}%)</span>
                    <span class="text-gray-900">Rs. {{ number_format($vatAmount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between pt-2 text-lg font-bold" style="border-top:2px solid #7C3AED">
                    <span style="color:#7C3AED">TOTAL</span>
                    <span style="color:#7C3AED">Rs. {{ number_format($quotation->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Terms --}}
        @if($quotation->terms)
        <div class="pt-6" style="border-top:1px solid #e5e7eb">
            <p class="font-semibold text-sm mb-2 text-gray-900">Terms & Conditions</p>
            <p class="text-gray-500 text-xs whitespace-pre-line leading-relaxed">{{ $quotation->terms }}</p>
        </div>
        @endif

        @if($quotation->payment_terms)
        <div class="pt-6" style="border-top:1px solid #e5e7eb">
            <p class="font-semibold text-sm mb-2 text-gray-900">Payment Terms</p>
            <p class="text-gray-500 text-xs">{{ $quotation->payment_terms }}</p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="mt-8 text-center">
            <p class="text-2xl mb-1" style="color:#7C3AED; font-family:cursive;">Thank you!</p>
            <p class="text-gray-500 text-xs">We look forward to capturing your special moments.</p>
        </div>

    </div>

    <p class="text-center text-gray-400 text-xs mt-6">{{ $studioName }} · Powered by CreativeStudio POS</p>

</body>
</html>