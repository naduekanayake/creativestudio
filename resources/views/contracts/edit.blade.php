@extends('layouts.app')

@section('title', 'Edit Contract')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('contracts.show', $contract) }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Edit Contract</h1>
            <p class="text-gray-400 text-sm">{{ $contract->contract_number }}</p>
        </div>
    </div>

    <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <form method="POST" action="{{ route('contracts.update', $contract) }}">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Contract Title *</label>
                <input type="text" name="title" value="{{ $contract->title }}" required
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Client *</label>
                    <select name="client_id" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $contract->client_id === $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Link Job</label>
                    <select name="job_id"
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="">No job linked</option>
                        @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ $contract->job_id === $job->id ? 'selected' : '' }}>{{ $job->job_number }} - {{ $job->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Type *</label>
                    <select name="type" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        @foreach(['Wedding', 'Event', 'Commercial', 'Portrait', 'Other'] as $type)
                        <option value="{{ $type }}" {{ $contract->type === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Status *</label>
                    <select name="status" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        @foreach(['Draft', 'Sent', 'Signed', 'Completed', 'Cancelled'] as $status)
                        <option value="{{ $status }}" {{ $contract->status === $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Contract Date *</label>
                    <input type="date" name="contract_date" value="{{ $contract->contract_date->format('Y-m-d') }}" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Event Date</label>
                    <input type="date" name="event_date" value="{{ $contract->event_date ? $contract->event_date->format('Y-m-d') : '' }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Event Location</label>
                    <input type="text" name="event_location" value="{{ $contract->event_location }}"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Total Amount (Rs.) *</label>
                    <input type="number" name="total_amount" value="{{ $contract->total_amount }}" min="0" step="0.01" required
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Advance Amount (Rs.)</label>
                    <input type="number" name="advance_amount" value="{{ $contract->advance_amount }}" min="0" step="0.01"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
            </div>

            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Terms & Conditions</label>
                <textarea name="terms" rows="5"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">{{ $contract->terms }}</textarea>
            </div>

            <div class="mb-6">
                <label class="text-gray-400 text-xs mb-1 block">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">{{ $contract->notes }}</textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('contracts.show', $contract) }}"
                   class="flex-1 text-center text-sm font-medium py-2 rounded-lg transition-colors"
                   :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Update Contract
                </button>
            </div>
        </form>
    </div>
</div>

@endsection