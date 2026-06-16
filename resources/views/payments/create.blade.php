@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('payments.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Record Payment</h1>
            <p class="text-gray-400 text-sm">Payments &gt; Record Payment</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'"
         x-data="paymentForm()">

        <form method="POST" action="{{ route('payments.store') }}">
            @csrf

            {{-- Payment Number --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Payment Number</label>
                <input type="text" name="payment_number" value="{{ $nextNumber }}" readonly
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
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Linked Invoice --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">
                    Link to Invoice
                    <span class="text-gray-500 ml-1">(optional — auto-fills amount & updates invoice status)</span>
                </label>
                <select name="invoice_id" x-model="selectedInvoiceId" @change="loadInvoiceAmount()"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="">No invoice linked</option>
                </select>
                <p class="text-gray-500 text-xs mt-1" x-show="!selectedClientId">Select a client first to see their unpaid invoices.</p>
            </div>

            {{-- Invoice Balance Info --}}
            <div x-show="invoiceBalance > 0" class="mb-4 p-3 rounded-lg bg-orange-500/20">
                <p class="text-orange-400 text-xs">
                    Balance due on selected invoice:
                    <span class="font-bold" x-text="'Rs. ' + invoiceBalance.toLocaleString()"></span>
                </p>
            </div>

            {{-- Amount & Method --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Amount (Rs.) *</label>
                    <input type="number" name="amount" x-model="amount" min="0.01" step="0.01" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="0.00"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Payment Method *</label>
                    <select name="method" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Online">Online</option>
                        <option value="Card">Card</option>
                    </select>
                </div>
            </div>

            {{-- Date & Status --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Payment Date *</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Status *</label>
                    <select name="status" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="Completed">Completed</option>
                        <option value="Pending">Pending</option>
                        <option value="Failed">Failed</option>
                        <option value="Refunded">Refunded</option>
                    </select>
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="text-gray-400 text-xs mb-1 block">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Any additional notes..."></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('payments.index') }}"
                   class="flex-1 text-center text-sm font-medium py-2 rounded-lg transition-colors"
                   :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Record Payment
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
    function paymentForm() {
        return {
            selectedClientId: '',
            selectedInvoiceId: '',
            invoiceBalance: 0,
            amount: '',
            clientInvoicesMap: JSON.parse(document.getElementById('client-invoices').textContent),

            loadInvoices: function() {
                this.selectedInvoiceId = '';
                this.invoiceBalance = 0;
                this.amount = '';

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
                    this.amount = this.invoiceBalance;
                } else {
                    this.invoiceBalance = 0;
                }
            },

            init: function() {
                this.calculateTotals;
            }
        }
    }
</script>

@endsection