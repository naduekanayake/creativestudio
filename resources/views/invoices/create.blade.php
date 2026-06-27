@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')

<div x-data="invoiceForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- LEFT: FORM --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Create Invoice</h1>
                <p class="text-gray-400 text-sm mt-0.5">Invoices &gt; Create Invoice</p>
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
                    Save & Send
                </button>
            </div>
        </div>

        {{-- Step Indicator --}}
        <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-2">
            <template x-for="(label, i) in ['Client & Details', 'Services & Items', 'Payment Info', 'Review & Send']" :key="i">
                <div class="flex items-center gap-2 flex-shrink-0">
                    <div class="flex items-center gap-2" x-show="i > 0">
                        <div class="w-6 h-px" :style="dark ? 'background:#252840' : 'background:#e5e7eb'"></div>
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
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Client & Invoice Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Invoice Number</label>
                    <input type="text" x-model="form.invoice_number" readonly
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none cursor-not-allowed opacity-70"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Invoice Type *</label>
                    <select x-model="form.type"
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="Tax">Tax Invoice</option>
                        <option value="Advance">Advance Invoice</option>
                        <option value="Final">Final Invoice</option>
                    </select>
                </div>
                <div></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Issue Date *</label>
                    <input type="date" x-model="form.issue_date"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Due Date *</label>
                    <input type="date" x-model="form.due_date"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>
        </div>

        {{-- STEP 2: Services & Items --}}
        <div x-show="step === 2" class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">Services & Items</h3>
                <div class="flex items-center gap-2">
                    <select @change="loadPackage($event.target.value); $event.target.value=''"
                            class="text-xs rounded-lg px-3 py-1.5 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#d1d5db;border:1px solid #2d3154' : 'background:#f9fafb;color:#374151;border:1px solid #e5e7eb'">
                        <option value="">+ Load from Package</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }} — Rs. {{ number_format($package->price) }}</option>
                        @endforeach
                    </select>
                    <button type="button" @click="addItem()"
                            class="bg-primary hover:bg-primary-hover text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Item
                    </button>
                </div>
            </div>

            <div class="space-y-2 mb-4 overflow-x-auto"><div style="min-width:560px">
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
</div>

        {{-- STEP 3: Payment Info --}}
        <div x-show="step === 3" class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Payment Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Amount Paid (Rs.)</label>
                    <input type="number" x-model.number="form.paid_amount" min="0" @input="calculateTotals()"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="0"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Balance Due (Auto)</label>
                    <div class="w-full text-sm rounded-lg px-3 py-2 font-semibold"
                         :class="totals.balance > 0 ? 'text-orange-400' : 'text-green-400'"
                         :style="dark ? 'background:#252840;border:1px solid #2d3154' : 'background:#f9fafb;border:1px solid #e5e7eb'"
                         x-text="'Rs. ' + totals.balance.toLocaleString()"></div>
                </div>
            </div>

            <div class="rounded-lg p-3 mb-4"
                 :class="totals.paymentStatus === 'Paid' ? 'bg-green-500/20' : (totals.paymentStatus === 'Partial' ? 'bg-orange-500/20' : 'bg-red-500/20')">
                <p class="text-xs font-medium"
                   :class="totals.paymentStatus === 'Paid' ? 'text-green-400' : (totals.paymentStatus === 'Partial' ? 'text-orange-400' : 'text-red-400')"
                   x-text="'Payment Status: ' + totals.paymentStatus"></p>
            </div>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Payment Terms</label>
                <select x-model="form.payment_terms"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="Due on Receipt">Due on Receipt</option>
                    <option value="Net 7 Days">Net 7 Days</option>
                    <option value="Net 14 Days">Net 14 Days</option>
                    <option value="Net 30 Days">Net 30 Days</option>
                    <option value="50% Advance, Balance Before Delivery">50% Advance, Balance Before Delivery</option>
                </select>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Terms & Notes</label>
                <textarea x-model="form.terms" rows="4"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Thank you for your business."></textarea>
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
                    <span class="text-gray-400">Invoice Type</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'" x-text="form.type"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Invoice Number</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'" x-text="form.invoice_number"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Total Items</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'" x-text="form.items.length"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Amount Paid</span>
                    <span class="text-green-400" x-text="'Rs. ' + (form.paid_amount || 0).toLocaleString()"></span>
                </div>
                <div class="flex justify-between text-sm pt-2" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
                    <span class="text-gray-400 font-medium">Total Amount</span>
                    <span class="text-primary font-bold text-lg" x-text="'Rs. ' + totals.total.toLocaleString()"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400 font-medium">Balance Due</span>
                    <span class="font-bold" :class="totals.balance > 0 ? 'text-orange-400' : 'text-green-400'" x-text="'Rs. ' + totals.balance.toLocaleString()"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Payment Status</span>
                    <span class="font-medium px-2 py-0.5 rounded-full text-xs"
                          :class="totals.paymentStatus === 'Paid' ? 'bg-green-500/20 text-green-400' : (totals.paymentStatus === 'Partial' ? 'bg-orange-500/20 text-orange-400' : 'bg-red-500/20 text-red-400')"
                          x-text="totals.paymentStatus"></span>
                </div>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Invoice Status</label>
                <select x-model="form.status"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="Draft">Draft</option>
                    <option value="Sent">Sent</option>
                    <option value="Paid">Paid</option>
                    <option value="Overdue">Overdue</option>
                    <option value="Cancelled">Cancelled</option>
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
                Save Invoice
            </button>
        </div>

    </div>

    {{-- RIGHT: LIVE PREVIEW --}}
    <div class="rounded-xl p-5 h-fit sticky top-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Invoice Preview</h3>
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
                    <p class="text-white font-bold" x-text="form.type.toUpperCase() + ' INVOICE'"></p>
                    <p class="text-primary text-xs" x-text="form.invoice_number"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4 text-xs">
                <div>
                    <p class="text-primary mb-1">FROM</p>
                    <p class="text-white font-medium">Creative Studio</p>
                    <p class="text-gray-500">No. 45, Park Road, Colombo 05</p>
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
                    <p class="text-gray-500">DUE DATE</p>
                    <p class="text-white" x-text="formatDate(form.due_date)"></p>
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
                        <th class="text-left py-1">ITEM</th>
                        <th class="text-right py-1">QTY</th>
                        <th class="text-right py-1">PRICE</th>
                        <th class="text-right py-1">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in form.items" :key="index">
                        <tr class="text-white" style="border-bottom:1px solid #1a1d2e">
                            <td class="py-1.5" x-text="index + 1"></td>
                            <td class="py-1.5" x-text="item.item_name || '-'"></td>
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
                    <span class="text-primary">TOTAL</span>
                    <span class="text-primary" x-text="'Rs. ' + totals.total.toLocaleString()"></span>
                </div>
                <div class="flex justify-between" x-show="form.paid_amount > 0">
                    <span class="text-green-400">PAID</span>
                    <span class="text-green-400" x-text="'Rs. ' + (form.paid_amount || 0).toLocaleString()"></span>
                </div>
                <div class="flex justify-between font-semibold" x-show="totals.balance > 0">
                    <span class="text-orange-400">BALANCE DUE</span>
                    <span class="text-orange-400" x-text="'Rs. ' + totals.balance.toLocaleString()"></span>
                </div>
            </div>

            <p class="text-gray-500 text-center text-xs">Thank you for choosing us!</p>
        </div>
    </div>
</div>

<form id="invoiceSubmitForm" method="POST" action="{{ route('invoices.store') }}" class="hidden">
    @csrf
    <div id="hiddenFields"></div>
</form>

@php
    $clientsArray = $clients->map(function($c) {
        return ['id' => $c->id, 'name' => $c->name, 'address' => $c->address, 'city' => $c->city];
    })->values()->toArray();

    $packagesArray = $packages->map(function($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float) $p->price,
            'description' => $p->description,
            'features' => $p->features ?? [],
        ];
    })->values()->toArray();

    $quotationItemsArray = [];
    $quotationDataArray = null;
    if ($fromQuotation) {
        $quotationDataArray = [
            'client_id' => $fromQuotation->client_id,
            'project_event' => $fromQuotation->project_event,
            'discount_percent' => (float) $fromQuotation->discount_percent,
            'vat_percent' => (float) $fromQuotation->vat_percent,
            'quotation_id' => $fromQuotation->id,
        ];
        $quotationItemsArray = $fromQuotation->items->map(function($item) {
            return ['item_name' => $item->item_name, 'description' => $item->description, 'qty' => $item->qty, 'unit_price' => (float) $item->unit_price];
        })->values()->toArray();
    }

    $pageDataArray = [
        'clients' => $clientsArray,
        'packages' => $packagesArray,
        'fromQuotation' => $quotationDataArray,
        'quotationItems' => $quotationItemsArray,
        'nextNumber' => $nextNumber,
        'today' => date('Y-m-d'),
        'dueDate' => date('Y-m-d', strtotime('+7 days')),
    ];
@endphp

<script type="application/json" id="page-data">@json($pageDataArray)</script>

<script>
    var pageData = JSON.parse(document.getElementById('page-data').textContent);
    var clientsData = pageData.clients;
    var packagesData = pageData.packages;

    function invoiceForm() {
        return {
            step: 1,
            form: {
                client_id: pageData.fromQuotation ? String(pageData.fromQuotation.client_id) : '',
                client_name: '',
                client_address: '',
                client_city: '',
                project_event: pageData.fromQuotation ? (pageData.fromQuotation.project_event || '') : '',
                quotation_id: pageData.fromQuotation ? pageData.fromQuotation.quotation_id : null,
                invoice_number: pageData.nextNumber,
                type: 'Tax',
                issue_date: pageData.today,
                due_date: pageData.dueDate,
                discount_percent: pageData.fromQuotation ? pageData.fromQuotation.discount_percent : 0,
                vat_percent: pageData.fromQuotation ? pageData.fromQuotation.vat_percent : 18,
                paid_amount: 0,
                payment_terms: 'Due on Receipt',
                terms: 'Thank you for your business.\nPlease make payment via bank transfer.\nLate payments may incur additional charges.',
                status: 'Draft',
                items: pageData.quotationItems.length > 0 ? pageData.quotationItems : [
                    { item_name: '', description: '', qty: 1, unit_price: 0 }
                ]
            },
            totals: { subTotal: 0, discount: 0, vat: 0, total: 0, balance: 0, paymentStatus: 'Unpaid' },

            updateClientInfo: function() {
                var selectedId = parseInt(this.form.client_id);
                for (var i = 0; i < clientsData.length; i++) {
                    if (clientsData[i].id === selectedId) {
                        this.form.client_name = clientsData[i].name || '';
                        this.form.client_address = clientsData[i].address || '';
                        this.form.client_city = clientsData[i].city || '';
                        return;
                    }
                }
                this.form.client_name = '';
                this.form.client_address = '';
                this.form.client_city = '';
            },

            loadPackage: function(packageId) {
                if (!packageId) return;
                var pkg = null;
                for (var i = 0; i < packagesData.length; i++) {
                    if (packagesData[i].id === parseInt(packageId)) { pkg = packagesData[i]; break; }
                }
                if (!pkg) return;

                var features = (pkg.features && pkg.features.length > 0) ? pkg.features.join(', ') : (pkg.description || '');
                var newItem = {
                    item_name: pkg.name,
                    description: features,
                    qty: 1,
                    unit_price: pkg.price
                };

                var hasContent = this.form.items.some(function(it) { return it.item_name; });
                if (hasContent) {
                    this.form.items.push(newItem);
                } else {
                    this.form.items = [newItem];
                }
                this.calculateTotals();
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
                    subTotal += (this.form.items[i].qty || 0) * (this.form.items[i].unit_price || 0);
                }
                var discount = subTotal * ((this.form.discount_percent || 0) / 100);
                var afterDiscount = subTotal - discount;
                var vat = afterDiscount * ((this.form.vat_percent || 0) / 100);
                var total = afterDiscount + vat;
                var paid = this.form.paid_amount || 0;
                var balance = Math.max(0, total - paid);
                var paymentStatus = paid >= total && total > 0 ? 'Paid' : (paid > 0 ? 'Partial' : 'Unpaid');

                this.totals.subTotal = subTotal;
                this.totals.discount = discount;
                this.totals.vat = vat;
                this.totals.total = total;
                this.totals.balance = balance;
                this.totals.paymentStatus = paymentStatus;
            },

            formatDate: function(dateStr) {
                if (!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            },

            submitForm: function(status) {
                if (!this.form.client_id) { alert('Please select a client'); this.step = 1; return; }
                if (this.form.items.length === 0 || !this.form.items[0].item_name) { alert('Please add at least one item'); this.step = 2; return; }

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
                addField('quotation_id', this.form.quotation_id || '');
                addField('invoice_number', this.form.invoice_number);
                addField('type', this.form.type);
                addField('project_event', this.form.project_event);
                addField('issue_date', this.form.issue_date);
                addField('due_date', this.form.due_date);
                addField('discount_percent', this.form.discount_percent);
                addField('vat_percent', this.form.vat_percent);
                addField('paid_amount', this.form.paid_amount);
                addField('payment_terms', this.form.payment_terms);
                addField('terms', this.form.terms);
                addField('status', this.form.status);

                for (var i = 0; i < this.form.items.length; i++) {
                    addField('items[' + i + '][item_name]', this.form.items[i].item_name);
                    addField('items[' + i + '][description]', this.form.items[i].description);
                    addField('items[' + i + '][qty]', this.form.items[i].qty);
                    addField('items[' + i + '][unit_price]', this.form.items[i].unit_price);
                }

                document.getElementById('invoiceSubmitForm').submit();
            },

            init: function() {
                if (this.form.client_id) this.updateClientInfo();
                this.calculateTotals();
            }
        }
    }
</script>

@endsection
