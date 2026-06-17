@extends('layouts.app')

@section('title', 'Contract Details')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header with Actions (hidden in print) --}}
    <div class="flex items-center justify-between mb-6 print:hidden">
        <div class="flex items-center gap-3">
            <a href="{{ route('contracts.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $contract->contract_number }}</h1>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $contract->status_color }}">{{ $contract->status }}</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                    class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print
            </button>
            <a href="{{ route('contracts.edit', $contract) }}"
               class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
               :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                Edit
            </a>
        </div>
    </div>

    {{-- Status Update Bar (hidden in print) --}}
    <div class="rounded-xl p-4 mb-4 print:hidden" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="flex items-center gap-2">
            <span class="text-gray-400 text-xs">Update Status:</span>
            @foreach(['Draft', 'Sent', 'Signed', 'Completed', 'Cancelled'] as $status)
            <form method="POST" action="{{ route('contracts.update-status', $contract) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $status }}"/>
                <button type="submit"
                        class="text-xs px-3 py-1 rounded-lg transition-colors {{ $contract->status === $status ? 'bg-primary text-white' : (request()->cookie('dark') ? 'bg-dark-700 text-gray-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200') }}">
                    {{ $status }}
                </button>
            </form>
            @endforeach
        </div>
    </div>

    {{-- Contract Document (printable) --}}
    <div id="contract-print" class="rounded-xl p-8 bg-white text-gray-900" style="background:#ffffff">

        {{-- Letterhead --}}
        <div class="flex items-center justify-between mb-8 pb-6" style="border-bottom:2px solid #7C3AED">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Creative Studio</h2>
                    <p class="text-gray-500 text-sm">Photography & Films</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-purple-600">CONTRACT</p>
                <p class="text-gray-500 text-sm">{{ $contract->contract_number }}</p>
            </div>
        </div>

        {{-- Title --}}
        <h1 class="text-2xl font-bold text-center text-gray-900 mb-8">{{ $contract->title }}</h1>

        {{-- Parties --}}
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <p class="text-xs text-gray-500 mb-1">SERVICE PROVIDER</p>
                <p class="font-semibold text-gray-900">Creative Studio</p>
                <p class="text-sm text-gray-600">Photography & Films</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">CLIENT</p>
                <p class="font-semibold text-gray-900">{{ $contract->client->name }}</p>
                @if($contract->client->phone)
                <p class="text-sm text-gray-600">{{ $contract->client->phone }}</p>
                @endif
                @if($contract->client->email)
                <p class="text-sm text-gray-600">{{ $contract->client->email }}</p>
                @endif
            </div>
        </div>

        {{-- Event Details --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-500">TYPE</p>
                    <p class="text-sm font-medium text-gray-900">{{ $contract->type }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">EVENT DATE</p>
                    <p class="text-sm font-medium text-gray-900">{{ $contract->event_date ? $contract->event_date->format('d M Y') : 'TBD' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">LOCATION</p>
                    <p class="text-sm font-medium text-gray-900">{{ $contract->event_location ?? 'TBD' }}</p>
                </div>
            </div>
        </div>

        {{-- Financial --}}
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-3">Financial Terms</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Contract Value</span>
                    <span class="font-medium text-gray-900">Rs. {{ number_format($contract->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Advance Paid</span>
                    <span class="font-medium text-gray-900">Rs. {{ number_format($contract->advance_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm pt-2" style="border-top:1px solid #e5e7eb">
                    <span class="font-semibold text-gray-900">Balance Due</span>
                    <span class="font-bold text-purple-600">Rs. {{ number_format($contract->balance, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Terms --}}
        @if($contract->terms)
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-3">Terms & Conditions</h3>
            <div class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $contract->terms }}</div>
        </div>
        @endif

        {{-- Notes --}}
        @if($contract->notes)
        <div class="mb-8">
            <h3 class="font-semibold text-gray-900 mb-2">Additional Notes</h3>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $contract->notes }}</p>
        </div>
        @endif

        {{-- Signatures --}}
        <div class="grid grid-cols-2 gap-12 mt-12 pt-8">
            <div class="text-center">
                <div class="border-t-2 border-gray-400 pt-2">
                    <p class="text-sm font-medium text-gray-900">Service Provider</p>
                    <p class="text-xs text-gray-500">Creative Studio</p>
                </div>
            </div>
            <div class="text-center">
                <div class="border-t-2 border-gray-400 pt-2">
                    <p class="text-sm font-medium text-gray-900">Client</p>
                    <p class="text-xs text-gray-500">{{ $contract->client->name }}</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 pt-4" style="border-top:1px solid #e5e7eb">
            <p class="text-xs text-gray-400">Contract Date: {{ $contract->contract_date->format('d M Y') }} · Generated by Creative Studio POS</p>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #contract-print, #contract-print * { visibility: visible; }
        #contract-print { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border-radius: 0; }
        @page { margin: 1cm; }
    }
</style>

@endsection