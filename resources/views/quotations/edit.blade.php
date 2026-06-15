@extends('layouts.app')

@section('title', 'Edit Quotation')

@section('content')

<div x-data="quotationForm()" class="grid grid-cols-3 gap-4">

    {{-- LEFT: FORM --}}
    <div class="col-span-2 space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Edit Quotation</h1>
                <p class="text-gray-400 text-sm mt-0.5">Quotations &gt; Edit {{ $quotation->quotation_number }}</p>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="submitForm('Draft')"
                        class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                        :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Save as Draft
                </button>
                <button type="button" @click="submitForm('Sent')"
                        class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Preview & Send
                </button>
            </div>
        </div>

        {{-- Step Indicator --}}
        <div class="flex items-center gap-2 mb-4">
            <template x-for="(label, i) in ['Client & Details', 'Services & Items', 'Additional Info', 'Review & Send']" :key="i">
                <div class="flex items-center gap-2" :class="i > 0 ? 'flex-1' : ''">
                    <div class="flex items-center gap-2" x-show="i > 0">
                        <div class="flex-1 h-px" :style="dark ? 'background:#252840' : 'background:#e5e7eb'"></div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold"
                             :class="step === i+1 ? 'bg-primary text-white' : (step > i+1 ? 'bg-green-500 text-white' : '')"
                             :style="step <= i ? (dark ? 'background:#252840;color:#9ca3af' : 'background:#e5e7eb;color:#6b7280') : ''">
                            <span x-show="step <= i+1" x-text="i+1"></span>
                            <svg x-show="step > i+1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium" :class="step === i+1 ? 'text-primary' : 'text-gray-400'" x-text="label"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- STEP 1: Client & Details --}}
        <div x-show="step === 1" class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Client & Quotation Details</h3>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Client *</label>
                    <select x-model="form.client_id" @change="updateClientInfo()"
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="">Select client...</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Project / Event</label>
                    <input type="text" x-model="form.project_event"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="e.g. Wedding - Kasun & Nadeesha"/>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Quotation Number</label>
                    <input type="text" x-model="form.quotation_number" readonly
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none cursor-not-allowed opacity-70"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Issue Date *</label>
                    <input type="date" x-model="form.issue_date"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Valid Until *</label>
                    <input type="date" x-model="form.valid_until"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>
        </div>

        {{-- STEP 2: Services & Items --}}
        <div x-show="step === 2" class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">Services & Items</h3>
                <button type="button" @click="addItem()"
                        class="bg-primary hover:bg-primary-hover text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Item
                </button>
            </div>

            <div class="space-y-2 mb-4">
                <div class="grid text-gray-500 text-xs px-2" style="grid-template-columns: 2fr 2fr 0.7fr 1fr 1fr 0.4fr; gap: 8px;">
                    <span>Item / Service</span>
                    <span>Description</span>
                    <span>Qty</span>
                    <span>Unit Price</span>
                    <span>Total</span>
                    <span></span>
                </div>

                <template x-for="(item, index) in form.items" :key="index">
                    <div class="grid items-center" style="grid-template-columns: 2fr 2fr 0.7fr 1fr 1fr 0.4fr; gap: 8px;">
                        <input type="text" x-model="item.item_name" placeholder="Item name"
                               class="text-sm rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                        <input type="text" x-model="item.description" placeholder="Description"
                               class="text-sm rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                        <input type="number" x-model.number="item.qty" min="1" @input="calculateTotals()"
                               class="text-sm rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                        <input type="number" x-model.number="item.unit_price" min="0" @input="calculateTotals()"
                               class="text-sm rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary"
                               :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                        <div class="text-sm font-medium px-2" :class="dark ? 'text-white' : 'text-gray-900'"
                             x-text="(item.qty * item.unit_price).toLocaleString()"></div>
                        <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-400 p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- STEP 3: Additional Info --}}
        <div x-show="step === 3" class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Additional Information</h3>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Discount (%)</label>
                    <input type="number" x-model.number="form.discount_percent" min="0" max="100" @input="calculateTotals()"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">VAT (%)</label>
                    <input type="number" x-model.number="form.vat_percent" min="0" max="100" @input="calculateTotals()"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Payment Terms</label>
                <select x-model="form.payment_terms"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="50% Advance, Balance Before Delivery">50% Advance, Balance Before Delivery</option>
                    <option value="Full Payment in Advance">Full Payment in Advance</option>
                    <option value="30% Advance, 70% Before Delivery">30% Advance, 70% Before Delivery</option>
                    <option value="Full Payment on Delivery">Full Payment on Delivery</option>
                </select>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Terms & Conditions</label>
                <textarea x-model="form.terms" rows="5"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="A booking is confirmed only after 50% advance payment."></textarea>
            </div>
        </div>

        {{-- STEP 4: Review & Send --}}
        <div x-show="step === 4" class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Review & Send</h3>

            <div class="space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Client</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'" x-text="form.client_name || '-'"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Project / Event</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'" x-text="form.project_event || '-'"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Quotation Number</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'" x-text="form.quotation_number"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Items</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'" x-text="form.items.length"></span>
                </div>
                <div class="flex justify-between text-sm pt-2" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
                    <span class="text-gray-400 font-medium">Total Amount</span>
                    <span class="text-primary font-bold text-lg" x-text="'Rs. ' + totals.total.toLocaleString()"></span>
                </div>
            </div>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Status</label>
                <select x-model="form.status"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="Draft">Draft</option>
                    <option value="Sent">Sent</option>
                    <option value="Accepted">Accepted</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>
        </div>

        {{-- Navigation Buttons --}}
        <div class="flex justify-between">
            <button type="button" @click="step = Math.max(1, step - 1)" x-show="step > 1"
                    class="text-sm font-medium px-5 py-2 rounded-lg transition-colors"
                    :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                Back
            </button>
            <div x-show="step === 1"></div>
            <button type="button" @click="step = Math.min(4, step + 1)" x-show="step < 4"
                    class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors ml-auto">
                Next
            </button>
            <button type="button" @click="submitForm(form.status)" x-show="step === 4"
                    class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors ml-auto">
                Update Quotation
            </button>
        </div>

    </div>

    {{-- RIGHT: LIVE PREVIEW --}}
    <div class="rounded-xl p-5 h-fit sticky top-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">Quotation Preview</h3>
        </div>

        <div class="rounded-lg p-4" style="background:#0f1117">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-xs">CREATIVE STUDIO</p>
                        <p class="text-gray-500 text-xs">PHOTOGRAPHY & FILMS</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-white font-bold">QUOTATION</p>
                    <p class="text-primary text-xs" x-text="form.quotation_number"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4 text-xs">
                <div>
                    <p class="text-primary mb-1">FROM</p>
                    <p class="text-white font-medium">Creative Studio</p>
                    <p class="text-gray-500">No. 45, Park Road, Colombo 05</p>
                    <p class="text-gray-500">Sri Lanka</p>
                </div>
                <div>
                    <p class="text-primary mb-1">TO</p>
                    <p class="text-white font-medium" x-text="form.client_name || 'Select a client'"></p>
                    <p class="text-gray-500" x-text="form.client_address || ''"></p>
                    <p class="text-gray-500" x-text="form.client_city || ''"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-4 text-xs p-2 rounded" style="background:#1a1d2e">
                <div>
                    <p class="text-gray-500">ISSUE DATE</p>
                    <p class="text-white" x-text="formatDate(form.issue_date)"></p>
                </div>
                <div>
                    <p class="text-gray-500">VALID UNTIL</p>
                    <p class="text-white" x-text="formatDate(form.valid_until)"></p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500">PROJECT / EVENT</p>
                    <p class="text-white" x-text="form.project_event || '-'"></p>
                </div>
            </div>

            <table class="w-full text-xs mb-4">
                <thead>
                    <tr class="text-gray-500" style="border-bottom:1px solid #252840">
                        <th class="text-left py-1">#</th>
                        <th class="text-left py-1">ITEM/SERVICE</th>
                        <th class="text-right py-1">QTY</th>
                        <th class="text-right py-1">PRICE</th>
                        <th class="text-right py-1">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in form.items" :key="index">
                        <tr class="text-white" style="border-bottom:1px solid #1a1d2e">
                            <td class="py-1.5" x-text="index + 1"></td>
                            <td class="py-1.5">
                                <p x-text="item.item_name || '-'"></p>
                                <p class="text-gray-500" x-text="item.description || ''"></p>
                            </td>
                            <td class="text-right py-1.5" x-text="item.qty"></td>
                            <td class="text-right py-1.5" x-text="Number(item.unit_price).toLocaleString()"></td>
                            <td class="text-right py-1.5" x-text="(item.qty * item.unit_price).toLocaleString()"></td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="space-y-1 text-xs mb-4">
                <div class="flex justify-between">
                    <span class="text-gray-400">SUB TOTAL</span>
                    <span class="text-white" x-text="'Rs. ' + totals.subTotal.toLocaleString()"></span>
                </div>
                <div class="flex justify-between" x-show="form.discount_percent > 0">
                    <span class="text-gray-400" x-text="'DISCOUNT (' + form.discount_percent + '%)'"></span>
                    <span class="text-red-400" x-text="'- Rs. ' + totals.discount.toLocaleString()"></span>
                </div>
                <div class="flex justify-between" x-show="form.vat_percent > 0">
                    <span class="text-gray-400" x-text="'VAT (' + form.vat_percent + '%)'"></span>
                    <span class="text-white" x-text="'Rs. ' + totals.vat.toLocaleString()"></span>
                </div>
                <div class="flex justify-between pt-1 text-sm font-bold" style="border-top:1px solid #252840">
                    <span class="text-primary">TOTAL AMOUNT</span>
                    <span class="text-primary" x-text="'Rs. ' + totals.total.toLocaleString()"></span>
                </div>
            </div>

            <div x-show="form.terms" class="text-xs mb-3">
                <p class="text-gray-400 mb-1">TERMS & CONDITIONS</p>
                <p class="text-gray-500 whitespace-pre-line" x-text="form.terms" style="font-size:10px;line-height:1.4"></p>
            </div>

            <p class="text-gray-500 text-center text-xs">Thank you for choosing us!</p>
        </div>
    </div>
</div>

{{-- Form data for submission --}}
<form id="quotationSubmitForm" method="POST" action="{{ route('quotations.update', $quotation) }}" class="hidden">
    @csrf
    @method('PUT')
    <div id="hiddenFields"></div>
</form>

@php
    $clientsArray = $clients->map(function($c) {
        return [
            'id' => $c->id,
            'name' => $c->name,
            'address' => $c->address,
            'city' => $c->city,
        ];
    })->values()->toArray();

    $itemsArray = $quotation->items->map(function($item) {
        return [
            'item_name' => $item->item_name,
            'description' => $item->description,
            'qty' => $item->qty,
            'unit_price' => (float) $item->unit_price,
        ];
    })->values()->toArray();

    $pageDataArray = [
        'clients' => $clientsArray,
        'quotation' => [
            'client_id' => $quotation->client_id,
            'client_name' => $quotation->client->name,
            'client_address' => $quotation->client->address,
            'client_city' => $quotation->client->city,
            'project_event' => $quotation->project_event,
            'quotation_number' => $quotation->quotation_number,
            'issue_date' => $quotation->issue_date->format('Y-m-d'),
            'valid_until' => $quotation->valid_until->format('Y-m-d'),
            'discount_percent' => (float) $quotation->discount_percent,
            'vat_percent' => (float) $quotation->vat_percent,
            'payment_terms' => $quotation->payment_terms,
            'terms' => $quotation->terms,
            'status' => $quotation->status,
            'items' => $itemsArray,
        ],
    ];
@endphp

<script type="application/json" id="quotation-data">@json($pageDataArray)</script>

<script>
    var pageData = JSON.parse(document.getElementById('quotation-data').textContent);
    var clientsData = pageData.clients;
    var initialQuotation = pageData.quotation;

    function quotationForm() {
        return {
            step: 1,
            form: {
                client_id: String(initialQuotation.client_id),
                client_name: initialQuotation.client_name || '',
                client_address: initialQuotation.client_address || '',
                client_city: initialQuotation.client_city || '',
                project_event: initialQuotation.project_event || '',
                quotation_number: initialQuotation.quotation_number,
                issue_date: initialQuotation.issue_date,
                valid_until: initialQuotation.valid_until,
                discount_percent: initialQuotation.discount_percent,
                vat_percent: initialQuotation.vat_percent,
                payment_terms: initialQuotation.payment_terms || '',
                terms: initialQuotation.terms || '',
                status: initialQuotation.status,
                items: initialQuotation.items.length > 0 ? initialQuotation.items : [
                    { item_name: '', description: '', qty: 1, unit_price: 0 }
                ]
            },
            totals: {
                subTotal: 0,
                discount: 0,
                vat: 0,
                total: 0
            },

            updateClientInfo: function() {
                var selectedId = parseInt(this.form.client_id);
                var client = null;
                for (var i = 0; i < clientsData.length; i++) {
                    if (clientsData[i].id === selectedId) {
                        client = clientsData[i];
                        break;
                    }
                }
                if (client) {
                    this.form.client_name = client.name || '';
                    this.form.client_address = client.address || '';
                    this.form.client_city = client.city || '';
                } else {
                    this.form.client_name = '';
                    this.form.client_address = '';
                    this.form.client_city = '';
                }
            },

            addItem: function() {
                this.form.items.push({ item_name: '', description: '', qty: 1, unit_price: 0 });
            },

            removeItem: function(index) {
                if (this.form.items.length > 1) {
                    this.form.items.splice(index, 1);
                    this.calculateTotals();
                }
            },

            calculateTotals: function() {
                var subTotal = 0;
                for (var i = 0; i < this.form.items.length; i++) {
                    var item = this.form.items[i];
                    subTotal += (item.qty || 0) * (item.unit_price || 0);
                }
                var discount = subTotal * ((this.form.discount_percent || 0) / 100);
                var afterDiscount = subTotal - discount;
                var vat = afterDiscount * ((this.form.vat_percent || 0) / 100);
                var total = afterDiscount + vat;

                this.totals.subTotal = subTotal;
                this.totals.discount = discount;
                this.totals.vat = vat;
                this.totals.total = total;
            },

            formatDate: function(dateStr) {
                if (!dateStr) return '-';
                var d = new Date(dateStr);
                return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            },

            submitForm: function(status) {
                if (!this.form.client_id) {
                    alert('Please select a client');
                    this.step = 1;
                    return;
                }
                if (this.form.items.length === 0 || !this.form.items[0].item_name) {
                    alert('Please add at least one item');
                    this.step = 2;
                    return;
                }

                this.form.status = status;
                this.calculateTotals();

                var hiddenFields = document.getElementById('hiddenFields');
                hiddenFields.innerHTML = '';

                function addField(name, value) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;
                    hiddenFields.appendChild(input);
                }

                addField('client_id', this.form.client_id);
                addField('quotation_number', this.form.quotation_number);
                addField('project_event', this.form.project_event);
                addField('issue_date', this.form.issue_date);
                addField('valid_until', this.form.valid_until);
                addField('discount_percent', this.form.discount_percent);
                addField('vat_percent', this.form.vat_percent);
                addField('payment_terms', this.form.payment_terms);
                addField('terms', this.form.terms);
                addField('status', this.form.status);

                for (var i = 0; i < this.form.items.length; i++) {
                    var item = this.form.items[i];
                    addField('items[' + i + '][item_name]', item.item_name);
                    addField('items[' + i + '][description]', item.description);
                    addField('items[' + i + '][qty]', item.qty);
                    addField('items[' + i + '][unit_price]', item.unit_price);
                }

                document.getElementById('quotationSubmitForm').submit();
            },

            init: function() {
                this.calculateTotals();
            }
        }
    }
</script>

@endsection