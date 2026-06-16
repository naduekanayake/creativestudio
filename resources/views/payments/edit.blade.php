@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('payments.show', $payment) }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Edit Payment</h1>
            <p class="text-gray-400 text-sm">Payments &gt; {{ $payment->payment_number }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'"
         x-data="editPaymentForm()">

        <form method="POST" action="{{ route('payments.update', $payment) }}">
            @csrf
            @method('PUT')

            {{-- Payment Number --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Payment Number</label>
                <input type="text" value="{{ $payment->payment_number }}" readonly
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none cursor-not-allowed opacity-70"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
            </div>

            {{-- Client --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Client *</label>
                <select name="client_id" x-model="selectedClientId" @change="loadInvoices()" required
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="">Select client...</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $payment->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Linked Invoice --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">
                    Link to Invoice
                    <span class="text-gray-500 ml-1">(optional)</span>
                </label>
                <select name="invoice_id" x-model="selectedInvoiceId" @change="loadInvoiceAmount()"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="">No invoice linked</option>
                    @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}" {{ $payment->invoice_id == $invoice->id ? 'selected' : '' }}
                            data-balance="{{ $invoice->balance_due }}">
                        {{ $invoice->invoice_number }} — Rs. {{ number_format($invoice->balance_due, 2) }} due
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Amount & Method --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Amount (Rs.) *</label>
                    <input type="number" name="amount" value="{{ $payment->amount }}" min="0.01" step="0.01" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Payment Method *</label>
                    <select name="method" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        @foreach(['Cash', 'Bank Transfer', 'Cheque', 'Online', 'Card'] as $method)
                        <option value="{{ $method }}" {{ $payment->method === $method ? 'selected' : '' }}>
                            {{ $method }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Date & Status --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ $payment->payment_date->format('Y-m-d') }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Status *</label>
                    <select name="status" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        @foreach(['Completed', 'Pending', 'Failed', 'Refunded'] as $status)
                        <option value="{{ $status }}" {{ $payment->status === $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="text-gray-400 text-xs mb-1 block">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Any additional notes...">{{ $payment->notes }}</textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('payments.show', $payment) }}"
                   class="flex-1 text-center text-sm font-medium py-2 rounded-lg transition-colors"
                   :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>

@php
    $clientInvoicesMap = [];
    foreach($clients as $client) {
        $clientInvoices = \App\Models\Invoice::where('client_id', $client->id)
            ->whereIn('payment_status', ['Unpaid', 'Partial'])
            ->get()
            ->map(function($inv) {
                return [
                    'id' => $inv->id,
                    'number' => $inv->invoice_number,
                    'balance' => (float) ($inv->total_amount - $inv->paid_amount),
                ];
            })->values()->toArray();
        $clientInvoicesMap[$client->id] = $clientInvoices;
    }
@endphp

<script type="application/json" id="client-invoices">@json($clientInvoicesMap)</script>

<script>
    function editPaymentForm() {
        return {
            selectedClientId: '{{ $payment->client_id }}',
            selectedInvoiceId: '{{ $payment->invoice_id ?? "" }}',
            invoiceBalance: 0,
            clientInvoicesMap: JSON.parse(document.getElementById('client-invoices').textContent),

            loadInvoices: function() {
                this.selectedInvoiceId = '';
                this.invoiceBalance = 0;

                var clientId = this.selectedClientId;
                var select = document.querySelector('select[name="invoice_id"]');
                select.innerHTML = '<option value="">No invoice linked</option>';

                if (clientId && this.clientInvoicesMap[clientId]) {
                    var invoices = this.clientInvoicesMap[clientId];
                    for (var i = 0; i < invoices.length; i++) {
                        var opt = document.createElement('option');
                        opt.value = invoices[i].id;
                        opt.dataset.balance = invoices[i].balance;
                        opt.textContent = invoices[i].number + ' — Rs. ' + invoices[i].balance.toLocaleString() + ' due';
                        select.appendChild(opt);
                    }
                }
            },

            loadInvoiceAmount: function() {
                var select = document.querySelector('select[name="invoice_id"]');
                var option = select.options[select.selectedIndex];
                if (option && option.dataset.balance && option.value !== '') {
                    this.invoiceBalance = parseFloat(option.dataset.balance);
                } else {
                    this.invoiceBalance = 0;
                }
            },

            init: function() {}
        }
    }
</script>

@endsection