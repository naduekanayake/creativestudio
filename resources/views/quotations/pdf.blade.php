<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: DejaVu Sans, sans-serif; }
        body { color: #1f2937; font-size: 12px; line-height: 1.5; }
        .container { padding: 30px; }
        .header { display: table; width: 100%; border-bottom: 2px solid #7C3AED; padding-bottom: 15px; margin-bottom: 20px; }
        .header-left { display: table-cell; vertical-align: middle; width: 60%; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; width: 40%; }
        .logo { width: 55px; height: 55px; vertical-align: middle; }
        .studio-name { font-size: 18px; font-weight: bold; color: #1f2937; display: inline-block; vertical-align: middle; margin-left: 10px; }
        .studio-tagline { font-size: 9px; color: #6b7280; letter-spacing: 1px; }
        .doc-title { font-size: 26px; font-weight: bold; color: #7C3AED; }
        .doc-number { font-size: 12px; color: #6b7280; margin-top: 3px; }
        .info-section { display: table; width: 100%; margin-bottom: 20px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; }
        .label { color: #7C3AED; font-size: 10px; font-weight: bold; margin-bottom: 5px; }
        .bold { font-weight: bold; }
        .muted { color: #6b7280; }
        .meta-bar { background: #f9fafb; padding: 12px; border-radius: 6px; margin-bottom: 20px; display: table; width: 100%; }
        .meta-item { display: table-cell; width: 33%; }
        .meta-label { color: #6b7280; font-size: 9px; }
        .meta-value { font-weight: bold; font-size: 11px; margin-top: 2px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th { background: #7C3AED; color: white; padding: 8px; font-size: 10px; text-align: left; }
        table.items th.right, table.items td.right { text-align: right; }
        table.items td { padding: 8px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        .totals { width: 250px; margin-left: auto; }
        .totals-row { display: table; width: 100%; padding: 4px 0; }
        .totals-label { display: table-cell; color: #6b7280; }
        .totals-value { display: table-cell; text-align: right; }
        .total-final { border-top: 2px solid #7C3AED; padding-top: 8px; margin-top: 4px; font-size: 15px; font-weight: bold; }
        .total-final .totals-label, .total-final .totals-value { color: #7C3AED; }
        .terms { border-top: 1px solid #e5e7eb; padding-top: 15px; margin-top: 20px; }
        .terms-title { font-weight: bold; font-size: 11px; margin-bottom: 5px; }
        .footer { text-align: center; margin-top: 30px; }
        .footer-thanks { font-size: 18px; color: #7C3AED; }
        .red { color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                @if($logoData)
                <img src="{{ $logoData }}" class="logo"/>
                @endif
                <span class="studio-name">{{ strtoupper($studioName) }}</span>
                <div class="studio-tagline">{{ strtoupper($studioTagline) }}</div>
            </div>
            <div class="header-right">
                <div class="doc-title">QUOTATION</div>
                <div class="doc-number">{{ $quotation->quotation_number }}</div>
            </div>
        </div>

        {{-- From / To --}}
        <div class="info-section">
            <div class="info-col">
                <div class="label">FROM</div>
                <div class="bold">{{ $studioName }}</div>
                @if($studioAddress)<div class="muted">{{ $studioAddress }}</div>@endif
                @if($studioCity)<div class="muted">{{ $studioCity }}</div>@endif
                @if($studioPhone)<div class="muted">{{ $studioPhone }}</div>@endif
                @if($studioEmail)<div class="muted">{{ $studioEmail }}</div>@endif
            </div>
            <div class="info-col">
                <div class="label">TO</div>
                <div class="bold">{{ $quotation->client->name }}</div>
                @if($quotation->client->address)<div class="muted">{{ $quotation->client->address }}</div>@endif
                @if($quotation->client->city)<div class="muted">{{ $quotation->client->city }}</div>@endif
                @if($quotation->client->phone)<div class="muted">{{ $quotation->client->phone }}</div>@endif
                @if($quotation->client->email)<div class="muted">{{ $quotation->client->email }}</div>@endif
            </div>
        </div>

        {{-- Meta --}}
        <div class="meta-bar">
            <div class="meta-item">
                <div class="meta-label">ISSUE DATE</div>
                <div class="meta-value">{{ $quotation->issue_date->format('d M Y') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">VALID UNTIL</div>
                <div class="meta-value">{{ $quotation->valid_until->format('d M Y') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">PROJECT / EVENT</div>
                <div class="meta-value">{{ $quotation->project_event ?? '-' }}</div>
            </div>
        </div>

        {{-- Items --}}
        <table class="items">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ITEM / SERVICE</th>
                    <th>DESCRIPTION</th>
                    <th class="right">QTY</th>
                    <th class="right">UNIT PRICE</th>
                    <th class="right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="bold">{{ $item->item_name }}</td>
                    <td class="muted">{{ $item->description ?? '-' }}</td>
                    <td class="right">{{ $item->qty }}</td>
                    <td class="right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="right bold">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <div class="totals-row">
                <span class="totals-label">SUB TOTAL</span>
                <span class="totals-value">Rs. {{ number_format($quotation->sub_total, 2) }}</span>
            </div>
            @if($quotation->discount_percent > 0)
            <div class="totals-row">
                <span class="totals-label">DISCOUNT ({{ $quotation->discount_percent }}%)</span>
                <span class="totals-value red">- Rs. {{ number_format($quotation->sub_total * ($quotation->discount_percent / 100), 2) }}</span>
            </div>
            @endif
            @if($quotation->vat_percent > 0)
            @php
                $afterDiscount = $quotation->sub_total - ($quotation->sub_total * ($quotation->discount_percent / 100));
                $vatAmount = $afterDiscount * ($quotation->vat_percent / 100);
            @endphp
            <div class="totals-row">
                <span class="totals-label">VAT ({{ $quotation->vat_percent }}%)</span>
                <span class="totals-value">Rs. {{ number_format($vatAmount, 2) }}</span>
            </div>
            @endif
            <div class="totals-row total-final">
                <span class="totals-label">TOTAL</span>
                <span class="totals-value">Rs. {{ number_format($quotation->total_amount, 2) }}</span>
            </div>
        </div>

        {{-- Terms --}}
        @if($quotation->terms)
        <div class="terms">
            <div class="terms-title">Terms & Conditions</div>
            <div class="muted">{!! nl2br(e($quotation->terms)) !!}</div>
        </div>
        @endif

        @if($quotation->payment_terms)
        <div class="terms">
            <div class="terms-title">Payment Terms</div>
            <div class="muted">{{ $quotation->payment_terms }}</div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <div class="footer-thanks">Thank you!</div>
            <div class="muted">{{ $invoiceFooter ?? 'We look forward to capturing your special moments.' }}</div>
        </div>
    </div>
</body>
</html>