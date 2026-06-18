@extends('layouts.app')

@section('title', 'Settings')

@section('content')

<div class="max-w-4xl mx-auto" x-data="{ tab: 'studio' }">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Settings</h1>
        <p class="text-gray-400 text-sm mt-0.5">Manage your studio information and preferences</p>
    </div>

    @if(session('success'))
    <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-2 mb-4">
        <button @click="tab = 'studio'"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                :class="tab === 'studio' ? 'bg-primary text-white' : (dark ? 'bg-dark-700 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200')">
            Studio Info
        </button>
        <button @click="tab = 'bank'"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                :class="tab === 'bank' ? 'bg-primary text-white' : (dark ? 'bg-dark-700 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200')">
            Bank Details
        </button>
        <button @click="tab = 'invoice'"
                class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
                :class="tab === 'invoice' ? 'bg-primary text-white' : (dark ? 'bg-dark-700 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200')">
            Invoice Settings
        </button>
    </div>

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PATCH')

        {{-- Studio Info Tab --}}
        <div x-show="tab === 'studio'" class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Studio Information</h3>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Studio Name</label>
                    <input type="text" name="studio_name" value="{{ $settings['studio_name'] ?? 'Creative Studio' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Tagline</label>
                    <input type="text" name="studio_tagline" value="{{ $settings['studio_tagline'] ?? 'Photography & Films' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Email</label>
                    <input type="email" name="email" value="{{ $settings['email'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="info@creativestudio.lk"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Website</label>
                    <input type="text" name="website" value="{{ $settings['website'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="www.creativestudio.lk"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone</label>
                    <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="077 123 4567"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Phone 2</label>
                    <input type="text" name="phone2" value="{{ $settings['phone2'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="011 234 5678"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Address</label>
                    <input type="text" name="address" value="{{ $settings['address'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="No. 123, Main Street"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">City</label>
                    <input type="text" name="city" value="{{ $settings['city'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="Badulla"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Logo URL</label>
                    <input type="text" name="logo_url" value="{{ $settings['logo_url'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="https://yoursite.com/logo.png"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Currency</label>
                    <input type="text" name="currency" value="{{ $settings['currency'] ?? 'Rs.' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="Rs."/>
                </div>
            </div>
        </div>

        {{-- Bank Details Tab --}}
        <div x-show="tab === 'bank'" x-cloak class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Bank Account Details</h3>
            <p class="text-gray-400 text-xs mb-4">These details will appear on invoices for client payments.</p>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Bank Name</label>
                <input type="text" name="bank_name" value="{{ $settings['bank_name'] ?? '' }}"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="e.g. Commercial Bank"/>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Account Number</label>
                    <input type="text" name="bank_account" value="{{ $settings['bank_account'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="1234567890"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Branch</label>
                    <input type="text" name="bank_branch" value="{{ $settings['bank_branch'] ?? '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                           placeholder="Badulla"/>
                </div>
            </div>
        </div>

        {{-- Invoice Settings Tab --}}
        <div x-show="tab === 'invoice'" x-cloak class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
            <h3 class="font-semibold mb-4" :class="dark ? 'text-white' : 'text-gray-900'">Invoice Settings</h3>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Invoice Prefix</label>
                <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV' }}"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="INV"/>
                <p class="text-gray-500 text-xs mt-1">Example: INV-2026-0001</p>
            </div>

            <div>
                <label class="text-gray-400 text-xs mb-1 block">Invoice Footer Note</label>
                <textarea name="invoice_footer" rows="3"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Thank you for your business!">{{ $settings['invoice_footer'] ?? '' }}</textarea>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end mt-4">
            <button type="submit"
                    class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-6 py-2.5 rounded-lg transition-colors">
                Save Settings
            </button>
        </div>
    </form>
</div>

@endsection