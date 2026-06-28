@extends('layouts.app')

@section('title', 'Email Sharing')

@section('content')

<div x-data="emailApp()">

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Email Sharing</h1>
        <p class="text-gray-400 text-sm mt-0.5">Professional email templates for clients</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- LEFT --}}
    <div class="lg:col-span-1 space-y-4">

        {{-- Client Select --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Select Client</h3>
            <select x-model="selectedClientId" @change="updateClient()"
                    class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                    :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                <option value="">Select client...</option>
                @foreach($clients as $client)
                <option value="{{ $client->id }}"
                        data-name="{{ $client->name }}"
                        data-email="{{ $client->email }}">
                    {{ $client->name }}
                </option>
                @endforeach
            </select>

            <div x-show="clientName" class="mt-3 p-3 rounded-lg" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
                <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'" x-text="clientName"></p>
                <p class="text-xs text-gray-400 mt-0.5" x-text="clientEmail || 'No email address'"></p>
            </div>
        </div>

        {{-- Template Categories --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Categories</h3>
            <div class="space-y-1">
                <template x-for="(cat, index) in categories" :key="index">
                    <button @click="selectedCategory = index; selectedTemplate = null"
                            class="w-full text-left px-3 py-2 rounded-lg text-sm transition-colors"
                            :class="selectedCategory === index
                                ? 'bg-primary text-white'
                                : (dark ? 'text-gray-400 hover:bg-dark-700' : 'text-gray-600 hover:bg-gray-50')">
                        <span x-text="cat.icon + ' ' + cat.name"></span>
                        <span class="ml-1 text-xs opacity-60" x-text="'(' + cat.templates.length + ')'"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Linked Data --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Link Data (Optional)</h3>

            <label class="text-gray-400 text-xs mb-1 block">Invoice</label>
            <select x-model="selectedInvoice" @change="updateEmail()"
                    class="w-full text-sm rounded-lg px-3 py-2 mb-3 focus:outline-none"
                    :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                <option value="">No invoice</option>
                @foreach($invoices as $invoice)
                <option value="{{ $invoice->invoice_number }}-Rs.{{ number_format($invoice->total_amount, 0) }}">
                    {{ $invoice->invoice_number }} - Rs. {{ number_format($invoice->total_amount, 0) }}
                </option>
                @endforeach
            </select>

            <label class="text-gray-400 text-xs mb-1 block">Quotation</label>
            <select x-model="selectedQuotation" @change="updateEmail()"
                    class="w-full text-sm rounded-lg px-3 py-2 mb-3 focus:outline-none"
                    :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                <option value="">No quotation</option>
                @foreach($quotations as $quotation)
                <option value="{{ $quotation->quotation_number }}-Rs.{{ number_format($quotation->total_amount, 0) }}">
                    {{ $quotation->quotation_number }} - Rs. {{ number_format($quotation->total_amount, 0) }}
                </option>
                @endforeach
            </select>

            <label class="text-gray-400 text-xs mb-1 block">Job</label>
            <select x-model="selectedJob" @change="updateEmail()"
                    class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none"
                    :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                <option value="">No job</option>
                @foreach($jobs as $job)
                <option value="{{ $job->job_number }}-{{ $job->title }}">
                    {{ $job->job_number }} - {{ Str::limit($job->title, 30) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Template Buttons --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'"
                x-text="categories[selectedCategory]?.name + ' Templates'"></h3>
            <div class="grid grid-cols-2 gap-2">
                <template x-for="(template, i) in categories[selectedCategory]?.templates" :key="i">
                    <div class="relative group">
                        <button @click="selectTemplate(template)"
                                class="w-full text-left px-3 py-2 pr-16 rounded-lg text-xs transition-colors"
                                :class="selectedTemplate === template
                                    ? 'bg-primary/20 border border-primary text-primary'
                                    : (dark ? 'bg-dark-700 text-gray-300 hover:bg-dark-600' : 'bg-gray-50 text-gray-600 hover:bg-gray-100')">
                            <span x-text="template.label"></span>
                        </button>
                        <div class="absolute right-1 top-1 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click.stop="openEditModal(i, template)"
                                    class="p-1 rounded text-gray-400 hover:text-primary transition-colors"
                                    :style="dark ? 'background:#1a1d2e' : 'background:#fff'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button @click.stop="deleteTemplate(i)"
                                    class="p-1 rounded text-gray-400 hover:text-red-400 transition-colors"
                                    :style="dark ? 'background:#1a1d2e' : 'background:#fff'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
                <button @click="openAddModal()"
                        class="col-span-2 text-left px-3 py-2 rounded-lg text-xs transition-colors border-2 border-dashed"
                        :class="dark ? 'border-dark-600 text-gray-500 hover:border-primary hover:text-primary' : 'border-gray-200 text-gray-400 hover:border-primary hover:text-primary'">
                    + Add Custom Template
                </button>
            </div>
        </div>

        {{-- Email Composer --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Email Composer</h3>

            {{-- To --}}
            <div class="mb-3">
                <label class="text-gray-400 text-xs mb-1 block">To</label>
                <input type="email" x-model="emailTo"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="client@email.com"/>
            </div>

            {{-- Subject --}}
            <div class="mb-3">
                <label class="text-gray-400 text-xs mb-1 block">Subject</label>
                <input type="text" x-model="emailSubject"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="Email subject..."/>
            </div>

            {{-- Body --}}
            <div class="mb-3">
                <div class="flex justify-between mb-1">
                    <label class="text-gray-400 text-xs">Body</label>
                    <span class="text-xs text-gray-500" x-text="emailBody.length + ' chars'"></span>
                </div>
                <textarea x-model="emailBody" rows="8"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary resize-none"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Email body..."></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <button @click="copyEmail()"
                        class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors flex items-center justify-center gap-2"
                        :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="copied ? 'Copied!' : 'Copy Body'"></span>
                </button>
                <button @click="openMailClient()"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Open Mail Client
                </button>
            </div>

            <div x-show="copied" x-transition class="mt-2 text-center text-xs text-green-400">
                ✅ Email body copied!
            </div>
        </div>

        {{-- Preview --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Email Preview</h3>
            <div class="rounded-xl overflow-hidden" style="border:1px solid #252840">
                {{-- Email Header --}}
                <div class="px-4 py-3" style="background:#252840">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-gray-500 text-xs w-12">From:</span>
                        <span class="text-gray-300 text-xs">info@creativestudio.lk</span>
                    </div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-gray-500 text-xs w-12">To:</span>
                        <span class="text-gray-300 text-xs" x-text="emailTo || 'client@email.com'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 text-xs w-12">Subject:</span>
                        <span class="text-white text-xs font-medium" x-text="emailSubject || 'No subject'"></span>
                    </div>
                </div>
                {{-- Email Body --}}
                <div class="p-4" style="background:#0f1117">
                    <div class="mb-4 pb-4" style="border-bottom:1px solid #252840">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-xs">CREATIVE STUDIO</p>
                                <p class="text-gray-500 text-xs">Photography & Films</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 text-xs whitespace-pre-wrap leading-relaxed"
                       x-text="emailBody || 'Your email body will appear here...'"></p>
                    <div class="mt-4 pt-4 text-xs text-gray-600" style="border-top:1px solid #252840">
                        <p>Creative Studio Photography & Films</p>
                        <p>No. 45, Park Road, Colombo 05</p>
                        <p>077 123 4567 | info@creativestudio.lk</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add / Edit Modal --}}
<div x-show="showModal"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="rounded-xl w-full max-w-lg mx-4 p-5" style="background:#1a1d2e;border:1px solid #252840">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-white" x-text="editIndex !== null ? 'Edit Template' : 'Add Custom Template'"></h3>
            <button @click="showModal = false" class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="mb-3">
            <label class="text-gray-400 text-xs mb-1 block">Template Label *</label>
            <input type="text" x-model="modalLabel"
                   class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none"
                   style="background:#252840;color:#fff;border:1px solid #2d3154"
                   placeholder="e.g. Follow Up After Meeting"/>
        </div>

        <div class="mb-3" x-show="editIndex === null">
            <label class="text-gray-400 text-xs mb-1 block">Category</label>
            <select x-model="modalCategory"
                    class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none"
                    style="background:#252840;color:#fff;border:1px solid #2d3154">
                <template x-for="(cat, index) in categories" :key="index">
                    <option :value="index" x-text="cat.icon + ' ' + cat.name"></option>
                </template>
            </select>
        </div>

        <div class="mb-3">
            <label class="text-gray-400 text-xs mb-1 block">Subject *</label>
            <input type="text" x-model="modalSubject"
                   class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none"
                   style="background:#252840;color:#fff;border:1px solid #2d3154"
                   placeholder="Email subject..."/>
        </div>

        <div class="mb-4">
            <label class="text-gray-400 text-xs mb-1 block">Body *</label>
            <p class="text-gray-500 text-xs mb-2">Placeholders: {name} {invoice} {quotation} {job}</p>
            <textarea x-model="modalBody" rows="7"
                      class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none"
                      style="background:#252840;color:#fff;border:1px solid #2d3154"
                      placeholder="Dear {name},&#10;&#10;Email body here...&#10;&#10;Best regards,&#10;Creative Studio Team"></textarea>
        </div>

        <div class="flex gap-3">
            <button @click="showModal = false"
                    class="flex-1 text-sm font-medium py-2 rounded-lg text-gray-400"
                    style="background:#252840">Cancel</button>
            <button @click="saveTemplate()"
                    class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg"
                    x-text="editIndex !== null ? 'Update' : 'Save Template'">
            </button>
        </div>
    </div>
</div>

</div>

@php
    $clientsData = $clients->map(function($c) {
        return ['id' => $c->id, 'name' => $c->name, 'email' => $c->email];
    })->values()->toArray();
@endphp

<script type="application/json" id="clients-data">@json($clientsData)</script>

<script>
function emailApp() {
    return {
        selectedClientId: '',
        clientName: '',
        clientEmail: '',
        selectedCategory: 0,
        selectedTemplate: null,
        selectedInvoice: '',
        selectedQuotation: '',
        selectedJob: '',
        emailTo: '',
        emailSubject: '',
        emailBody: '',
        copied: false,
        showModal: false,
        editIndex: null,
        modalLabel: '',
        modalSubject: '',
        modalBody: '',
        modalCategory: 0,
        clientsData: JSON.parse(document.getElementById('clients-data').textContent),

        categories: [
            {
                name: 'Inquiry', icon: '📩',
                templates: [
                    {
                        label: 'Initial Response',
                        subject: 'Thank You for Contacting Creative Studio',
                        body: 'Dear {name},\n\nThank you for reaching out to Creative Studio! We are delighted to hear from you.\n\nWe specialize in Photography & Videography services for weddings, portraits, commercial shoots, and events.\n\nCould you please share more details about:\n• Event date & location\n• Type of service required\n• Any specific requirements\n\nWe would love to discuss how we can capture your special moments!\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                    {
                        label: 'Availability Confirm',
                        subject: 'Availability Confirmation - Creative Studio',
                        body: 'Dear {name},\n\nThank you for your inquiry!\n\nWe are pleased to confirm that we are available for your requested date. We would love to be part of your special occasion.\n\nPlease find our packages and pricing in the attached quotation.\n\nFeel free to contact us if you have any questions.\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                ]
            },
            {
                name: 'Quotation', icon: '📄',
                templates: [
                    {
                        label: 'Quotation Attached',
                        subject: 'Quotation from Creative Studio - {quotation}',
                        body: 'Dear {name},\n\nThank you for considering Creative Studio for your photography needs.\n\nPlease find your quotation details below:\n📋 Quotation: {quotation}\n\nThis quotation is valid for 14 days from the date of issue.\n\nTo confirm your booking, a 50% advance payment is required.\n\nPlease do not hesitate to contact us if you have any questions or require any modifications.\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                    {
                        label: 'Quotation Follow Up',
                        subject: 'Following Up - Quotation {quotation}',
                        body: 'Dear {name},\n\nI hope this email finds you well.\n\nI wanted to follow up regarding the quotation we sent recently:\n📋 {quotation}\n\nWe would love to confirm your booking and begin planning for your event. Our calendar is filling up quickly, so we recommend confirming at your earliest convenience.\n\nPlease let us know if you have any questions or if you would like to discuss any modifications to the quotation.\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                ]
            },
            {
                name: 'Invoice', icon: '🧾',
                templates: [
                    {
                        label: 'Invoice Sent',
                        subject: 'Invoice from Creative Studio - {invoice}',
                        body: 'Dear {name},\n\nPlease find your invoice details below:\n🧾 Invoice: {invoice}\n\nPayment Details:\nBank: XYZ Bank\nAccount Number: 1234567890\nAccount Name: Creative Studio\n\nPayment Terms: Due within 7 days of receipt\n\nPlease include your invoice number as the payment reference.\n\nIf you have any questions regarding this invoice, please do not hesitate to contact us.\n\nThank you for your business!\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                    {
                        label: 'Payment Reminder',
                        subject: 'Payment Reminder - Invoice {invoice}',
                        body: 'Dear {name},\n\nThis is a friendly reminder regarding your outstanding invoice:\n🧾 {invoice}\n\nIf you have already made the payment, please disregard this email and accept our apologies for any inconvenience.\n\nIf you have any questions or concerns regarding this invoice, please do not hesitate to contact us.\n\nThank you for your prompt attention to this matter.\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                    {
                        label: 'Payment Confirmed',
                        subject: 'Payment Received - Thank You!',
                        body: 'Dear {name},\n\nWe are pleased to confirm that we have received your payment. ✅\n\nYour booking is now fully confirmed and our team is excited to be part of your special occasion!\n\nIf you have any questions or special requests, please feel free to reach out.\n\nThank you for choosing Creative Studio!\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                ]
            },
            {
                name: 'Job Updates', icon: '📸',
                templates: [
                    {
                        label: 'Booking Confirmed',
                        subject: 'Booking Confirmation - {job}',
                        body: 'Dear {name},\n\nWe are thrilled to confirm your booking with Creative Studio! 🎉\n\n📸 Job Details: {job}\n\nOur team is looking forward to capturing your special moments. We will be in touch closer to the date with further details.\n\nIn the meantime, please feel free to share any specific requirements or ideas you may have.\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                    {
                        label: 'Shoot Completed',
                        subject: 'Thank You - Shoot Completed Successfully',
                        body: 'Dear {name},\n\nThank you for a wonderful session today! 🎊\n\n📸 {job}\n\nOur team had an incredible time capturing your special moments. We are now beginning the editing process and will keep you updated on the progress.\n\nEstimated delivery time: 4-6 weeks\n\nThank you for choosing Creative Studio. We look forward to sharing the beautiful results with you!\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                ]
            },
            {
                name: 'Delivery', icon: '📦',
                templates: [
                    {
                        label: 'Photos Ready',
                        subject: 'Your Photos are Ready! - Creative Studio',
                        body: 'Dear {name},\n\nWe are excited to share that your photos are now ready! 🎉📸\n\nYou can access your photos using the link below:\n🔗 [Insert Drive Link Here]\n\nPlease download your photos within 30 days as the link will expire after that period.\n\nWe would love to hear your feedback! If you have any questions or concerns, please do not hesitate to contact us.\n\nThank you for choosing Creative Studio. It was our pleasure to capture your special moments!\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                    {
                        label: 'Delivery Follow Up',
                        subject: 'Following Up - Your Creative Studio Photos',
                        body: 'Dear {name},\n\nI hope you are enjoying your photos from Creative Studio!\n\nWe wanted to follow up to ensure you received your photos and that everything meets your expectations.\n\nYour feedback means a lot to us. If you are happy with our services, we would greatly appreciate if you could leave us a review.\n\nThank you again for choosing Creative Studio!\n\nBest regards,\nCreative Studio Team\n077 123 4567 | info@creativestudio.lk'
                    },
                ]
            },
        ],

        updateClient: function() {
            var select = document.querySelector('select[x-model="selectedClientId"]');
            var option = select.options[select.selectedIndex];
            this.clientName = option.dataset.name || '';
            this.clientEmail = option.dataset.email || '';
            this.emailTo = this.clientEmail;
            this.updateEmail();
        },

        selectTemplate: function(template) {
            this.selectedTemplate = template;
            this.emailSubject = template.subject;
            this.updateEmail();
        },

        updateEmail: function() {
            if (!this.selectedTemplate) return;

            var body = this.selectedTemplate.body;
            var subject = this.selectedTemplate.subject;

            body = body.replace(/{name}/g, this.clientName || '[Client Name]');
            subject = subject.replace(/{name}/g, this.clientName || '[Client Name]');

            if (this.selectedInvoice) {
                var invParts = this.selectedInvoice.split('-');
                body = body.replace(/{invoice}/g, invParts[0] + ' | Rs. ' + invParts[1]);
                subject = subject.replace(/{invoice}/g, invParts[0]);
            } else {
                body = body.replace(/{invoice}/g, '[Invoice Details]');
                subject = subject.replace(/{invoice}/g, '[Invoice]');
            }

            if (this.selectedQuotation) {
                var quoParts = this.selectedQuotation.split('-');
                body = body.replace(/{quotation}/g, quoParts[0] + ' | Rs. ' + quoParts[1]);
                subject = subject.replace(/{quotation}/g, quoParts[0]);
            } else {
                body = body.replace(/{quotation}/g, '[Quotation Details]');
                subject = subject.replace(/{quotation}/g, '[Quotation]');
            }

            if (this.selectedJob) {
                var jobParts = this.selectedJob.split('-');
                body = body.replace(/{job}/g, jobParts[0] + ' | ' + jobParts.slice(1).join('-'));
                subject = subject.replace(/{job}/g, jobParts[0]);
            } else {
                body = body.replace(/{job}/g, '[Job Details]');
                subject = subject.replace(/{job}/g, '[Job]');
            }

            this.emailBody = body;
            this.emailSubject = subject;
        },

        openAddModal: function() {
            this.editIndex = null;
            this.modalLabel = '';
            this.modalSubject = '';
            this.modalBody = '';
            this.modalCategory = this.selectedCategory;
            this.showModal = true;
        },

        openEditModal: function(index, template) {
            this.editIndex = index;
            this.modalLabel = template.label;
            this.modalSubject = template.subject;
            this.modalBody = template.body;
            this.showModal = true;
        },

        saveTemplate: function() {
            if (!this.modalLabel || !this.modalSubject || !this.modalBody) {
                alert('Please fill in all fields!');
                return;
            }
            var newTemplate = {
                label: this.modalLabel,
                subject: this.modalSubject,
                body: this.modalBody
            };
            if (this.editIndex !== null) {
                this.categories[this.selectedCategory].templates[this.editIndex] = newTemplate;
            } else {
                var targetCat = parseInt(this.modalCategory);
                this.categories[targetCat].templates.push(newTemplate);
                this.selectedCategory = targetCat;
            }
            this.showModal = false;
            this.editIndex = null;
        },

        deleteTemplate: function(index) {
            if (!confirm('Delete this template?')) return;
            if (this.selectedTemplate === this.categories[this.selectedCategory].templates[index]) {
                this.selectedTemplate = null;
                this.emailBody = '';
                this.emailSubject = '';
            }
            this.categories[this.selectedCategory].templates.splice(index, 1);
        },

        copyEmail: function() {
            if (!this.emailBody) return;
            navigator.clipboard.writeText(this.emailBody).then(() => {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 3000);
            });
        },

        openMailClient: function() {
            if (!this.emailBody) {
                alert('Please select a template first!');
                return;
            }
            var mailto = 'mailto:' + encodeURIComponent(this.emailTo)
                + '?subject=' + encodeURIComponent(this.emailSubject)
                + '&body=' + encodeURIComponent(this.emailBody);
            window.location.href = mailto;
        }
    }
}
</script>

@endsection