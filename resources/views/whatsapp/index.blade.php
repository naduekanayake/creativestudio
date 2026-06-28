@extends('layouts.app')

@section('title', 'WhatsApp Messages')

@section('content')

<div x-data="whatsappApp()">

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">WhatsApp Messages</h1>
        <p class="text-gray-400 text-sm mt-0.5">Quick message templates for clients</p>
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
                        data-phone="{{ $client->phone }}">
                    {{ $client->name }}
                </option>
                @endforeach
            </select>
            <div x-show="clientName" class="mt-3 p-3 rounded-lg" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
                <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'" x-text="clientName"></p>
                <p class="text-xs text-gray-400 mt-0.5" x-text="clientPhone || 'No phone number'"></p>
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
            <select x-model="selectedInvoice" @change="updateMessage()"
                    class="w-full text-sm rounded-lg px-3 py-2 mb-3 focus:outline-none"
                    :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                <option value="">No invoice</option>
                @foreach($invoices as $invoice)
                <option value="{{ $invoice->invoice_number }}-Rs.{{ number_format($invoice->balance_due, 0) }}">
                    {{ $invoice->invoice_number }} - Rs. {{ number_format($invoice->balance_due, 0) }} due
                </option>
                @endforeach
            </select>

            <label class="text-gray-400 text-xs mb-1 block">Quotation</label>
            <select x-model="selectedQuotation" @change="updateMessage()"
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
            <select x-model="selectedJob" @change="updateMessage()"
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
                        {{-- Edit & Delete buttons --}}
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

                {{-- Add Custom Template --}}
                <button @click="openAddModal()"
                        class="col-span-2 text-left px-3 py-2 rounded-lg text-xs transition-colors border-2 border-dashed"
                        :class="dark ? 'border-dark-600 text-gray-500 hover:border-primary hover:text-primary' : 'border-gray-200 text-gray-400 hover:border-primary hover:text-primary'">
                    + Add Custom Template
                </button>
            </div>
        </div>

        {{-- Message Editor --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Message</h3>
                <span class="text-xs text-gray-400" x-text="message.length + ' characters'"></span>
            </div>
            <textarea x-model="message" rows="8"
                      class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary resize-none"
                      :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                      placeholder="Select a client and template to generate a message..."></textarea>

            <div class="flex gap-3 mt-3">
                <button @click="copyMessage()"
                        class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors flex items-center justify-center gap-2"
                        :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="copied ? 'Copied!' : 'Copy Message'"></span>
                </button>
                <button @click="openWhatsApp()"
                        class="flex-1 text-sm font-medium py-2 rounded-lg transition-colors flex items-center justify-center gap-2"
                        style="background:#25D366;color:#fff">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Open WhatsApp
                </button>
            </div>
            <div x-show="copied" x-transition class="mt-2 text-center text-xs text-green-400">
                ✅ Message copied to clipboard!
            </div>
        </div>

        {{-- Preview --}}
        <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-3 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">Preview</h3>
            <div class="rounded-xl p-4 max-w-sm" style="background:#0f1117">
                <div class="rounded-lg p-3 text-sm text-white whitespace-pre-wrap leading-relaxed"
                     style="background:#1a1d2e;max-width:280px"
                     x-text="message || 'Your message preview will appear here...'"></div>
                <p class="text-right text-gray-600 text-xs mt-1">Creative Studio</p>
            </div>
        </div>
    </div>
</div>

{{-- Add / Edit Template Modal --}}
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
                   class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                   style="background:#252840;color:#fff;border:1px solid #2d3154"
                   placeholder="e.g. Special Discount Offer"/>
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

        <div class="mb-4">
            <label class="text-gray-400 text-xs mb-1 block">Message Content *</label>
            <p class="text-gray-500 text-xs mb-2">Placeholders: {name} {invoice} {quotation} {job}</p>
            <textarea x-model="modalText" rows="8"
                      class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                      style="background:#252840;color:#fff;border:1px solid #2d3154"
                      placeholder="Dear {name},&#10;&#10;Your message here...&#10;&#10;*Creative Studio Photography & Films*"></textarea>
        </div>

        <div class="flex gap-3">
            <button @click="showModal = false"
                    class="flex-1 text-sm font-medium py-2 rounded-lg text-gray-400"
                    style="background:#252840">
                Cancel
            </button>
            <button @click="saveTemplate()"
                    class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors"
                    x-text="editIndex !== null ? 'Update Template' : 'Save Template'">
            </button>
        </div>
    </div>
</div>

</div>

@php
    $clientsData = $clients->map(function($c) {
        return ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone];
    })->values()->toArray();
@endphp

<script type="application/json" id="clients-data">@json($clientsData)</script>

<script>
function whatsappApp() {
    return {
        selectedClientId: '',
        clientName: '',
        clientPhone: '',
        selectedCategory: 0,
        selectedTemplate: null,
        selectedInvoice: '',
        selectedQuotation: '',
        selectedJob: '',
        message: '',
        copied: false,

        // Modal state
        showModal: false,
        editIndex: null,
        modalLabel: '',
        modalText: '',
        modalCategory: 0,

        clientsData: JSON.parse(document.getElementById('clients-data').textContent),

        categories: [
            {
                name: 'Greeting', icon: '👋',
                templates: [
                    { label: 'Initial Inquiry', text: 'Hello {name}! 👋\n\nThank you for reaching out to Creative Studio. We would love to capture your special moments!\n\nCould you please share more details about your event date, location, and requirements?\n\nLooking forward to hearing from you! 😊\n\n*Creative Studio Photography & Films*' },
                    { label: 'Welcome New Client', text: 'Dear {name},\n\nWelcome to the Creative Studio family! 🎉\n\nWe are thrilled to be part of your special journey. Our team is committed to delivering beautiful memories that last a lifetime.\n\nFeel free to reach out anytime!\n\n*Creative Studio Photography & Films*\n📞 077 123 4567' },
                ]
            },
            {
                name: 'Quotation', icon: '📄',
                templates: [
                    { label: 'Quotation Sent', text: 'Dear {name},\n\nThank you for choosing Creative Studio! 🙏\n\nWe have prepared a quotation for you:\n📋 *{quotation}*\n\nPlease review and let us know if you have any questions.\n\n*Creative Studio Photography & Films*' },
                    { label: 'Quotation Follow Up', text: 'Hello {name}! 😊\n\nJust following up on the quotation we sent recently.\n📋 *{quotation}*\n\nWe would love to confirm your booking! The date is filling up quickly. 📅\n\n*Creative Studio Photography & Films*' },
                    { label: 'Quotation Reminder', text: 'Dear {name},\n\nThis is a gentle reminder that your quotation *{quotation}* will expire soon.\n\nPlease confirm at your earliest convenience.\n\n*Creative Studio Photography & Films*\n📞 077 123 4567' },
                ]
            },
            {
                name: 'Invoice & Payment', icon: '💰',
                templates: [
                    { label: 'Invoice Sent', text: 'Dear {name},\n\nPlease find your invoice details below:\n🧾 *{invoice}*\n\nKindly make the payment at your earliest convenience.\n\n*Bank Details:*\nBank: XYZ Bank\nAccount: 1234567890\nName: Creative Studio\n\nThank you! 🙏\n\n*Creative Studio Photography & Films*' },
                    { label: 'Payment Reminder', text: 'Dear {name},\n\nThis is a friendly reminder regarding your outstanding payment:\n💳 *{invoice}*\n\nPlease make the payment to confirm your booking.\n\nThank you! 🙏\n\n*Creative Studio Photography & Films*' },
                    { label: 'Payment Received', text: 'Dear {name},\n\nWe have received your payment! ✅\n\nYour booking is now confirmed! 🎉\n\nWe are excited to capture your special moments!\n\n*Creative Studio Photography & Films*' },
                ]
            },
            {
                name: 'Job Updates', icon: '📸',
                templates: [
                    { label: 'Booking Confirmed', text: 'Dear {name},\n\nYour booking is CONFIRMED! 🎉\n📸 *{job}*\n\nWe are excited to be part of your special day!\n\n*Creative Studio Photography & Films*' },
                    { label: 'Day Before Reminder', text: 'Dear {name},\n\nJust a reminder — we have your shoot scheduled for *TOMORROW*! 📅\n📸 *{job}*\n\nOur team will be ready on time. See you soon! 😊\n\n*Creative Studio Photography & Films*' },
                    { label: 'Shoot Complete', text: 'Dear {name},\n\nThank you for an amazing session today! 🎊\n📸 *{job}*\n\nEstimated delivery: 4-6 weeks\n\nThank you for choosing Creative Studio! 🙏' },
                ]
            },
            {
                name: 'Delivery', icon: '📦',
                templates: [
                    { label: 'Photos Ready', text: 'Dear {name},\n\nGreat news! Your photos are READY! 🎉📸\n\n🔗 [Drive Link Here]\n\nWe hope you love them! Please share your feedback. 😊\n\n*Creative Studio Photography & Films*' },
                    { label: 'Delivery Reminder', text: 'Dear {name},\n\nYour edited photos/videos are ready for delivery! 📦\n\nKindly confirm your preferred delivery method:\n✅ Google Drive\n✅ USB Drive\n✅ Physical Album\n\n*Creative Studio Photography & Films*' },
                ]
            },
        ],

        openAddModal: function() {
            this.editIndex = null;
            this.modalLabel = '';
            this.modalText = '';
            this.modalCategory = this.selectedCategory;
            this.showModal = true;
        },

        openEditModal: function(index, template) {
            this.editIndex = index;
            this.modalLabel = template.label;
            this.modalText = template.text;
            this.showModal = true;
        },

        saveTemplate: function() {
            if (!this.modalLabel || !this.modalText) {
                alert('Please fill in both label and message!');
                return;
            }
            if (this.editIndex !== null) {
                // Update existing
                this.categories[this.selectedCategory].templates[this.editIndex] = {
                    label: this.modalLabel,
                    text: this.modalText
                };
                // Refresh selected template if it was the one being edited
                if (this.selectedTemplate && this.selectedTemplate.label === this.modalLabel) {
                    this.selectedTemplate = this.categories[this.selectedCategory].templates[this.editIndex];
                    this.updateMessage();
                }
            } else {
                // Add new
                var targetCategory = parseInt(this.modalCategory);
                this.categories[targetCategory].templates.push({
                    label: this.modalLabel,
                    text: this.modalText
                });
                this.selectedCategory = targetCategory;
            }
            this.showModal = false;
            this.editIndex = null;
            this.modalLabel = '';
            this.modalText = '';
        },

        deleteTemplate: function(index) {
            if (!confirm('Delete this template?')) return;
            var template = this.categories[this.selectedCategory].templates[index];
            if (this.selectedTemplate === template) {
                this.selectedTemplate = null;
                this.message = '';
            }
            this.categories[this.selectedCategory].templates.splice(index, 1);
        },

        updateClient: function() {
            var select = document.querySelector('select[x-model="selectedClientId"]');
            var option = select.options[select.selectedIndex];
            this.clientName = option.dataset.name || '';
            this.clientPhone = option.dataset.phone || '';
            this.updateMessage();
        },

        selectTemplate: function(template) {
            this.selectedTemplate = template;
            this.updateMessage();
        },

        updateMessage: function() {
            if (!this.selectedTemplate) return;
            var msg = this.selectedTemplate.text;
            msg = msg.replace(/{name}/g, this.clientName || '[Client Name]');
            if (this.selectedInvoice) {
                var invParts = this.selectedInvoice.split('-');
                msg = msg.replace(/{invoice}/g, invParts[0] + ' | Due: ' + invParts[1]);
            } else {
                msg = msg.replace(/{invoice}/g, '[Invoice Details]');
            }
            if (this.selectedQuotation) {
                var quoParts = this.selectedQuotation.split('-');
                msg = msg.replace(/{quotation}/g, quoParts[0] + ' | ' + quoParts[1]);
            } else {
                msg = msg.replace(/{quotation}/g, '[Quotation Details]');
            }
            if (this.selectedJob) {
                var jobParts = this.selectedJob.split('-');
                msg = msg.replace(/{job}/g, jobParts[0] + ' | ' + jobParts.slice(1).join('-'));
            } else {
                msg = msg.replace(/{job}/g, '[Job Details]');
            }
            this.message = msg;
        },

        copyMessage: function() {
            if (!this.message) return;
            navigator.clipboard.writeText(this.message).then(() => {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 3000);
            });
        },

        openWhatsApp: function() {
            if (!this.message) {
                alert('Please select a template first!');
                return;
            }
            var phone = this.clientPhone ? this.clientPhone.replace(/\D/g, '') : '';
            if (phone && phone.startsWith('0')) {
                phone = '94' + phone.substring(1);
            }
            var encodedMsg = encodeURIComponent(this.message);
            var url = phone
                ? 'https://wa.me/' + phone + '?text=' + encodedMsg
                : 'https://wa.me/?text=' + encodedMsg;
            window.open(url, '_blank');
        }
    }
}
</script>

@endsection