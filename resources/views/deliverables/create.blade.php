@extends('layouts.app')

@section('title', 'Add Deliverable')

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('deliverables.index') }}" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Add Deliverable</h1>
            <p class="text-gray-400 text-sm">Deliverables &gt; New</p>
        </div>
    </div>

    <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <form method="POST" action="{{ route('deliverables.store') }}">
            @csrf

            {{-- Title --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Title *</label>
                <input type="text" name="title" required
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="e.g. Wedding Photos - Kasun & Nadeesha"/>
            </div>

            {{-- Client & Type --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Client *</label>
                    <select name="client_id" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        <option value="">Select client...</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Type *</label>
                    <select name="type" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        @foreach(['Photos', 'Videos', 'Album', 'Raw Files', 'Edited Files', 'Prints', 'Other'] as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Job --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Linked Job <span class="text-gray-500">(optional)</span></label>
                <select name="job_id"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="">No job linked</option>
                    @foreach($jobs as $job)
                    <option value="{{ $job->id }}">{{ $job->job_number }} - {{ $job->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Delivery Method --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Delivery Method</label>
                <select name="delivery_method"
                        class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                        :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                    <option value="">Select method...</option>
                    <option value="Google Drive">Google Drive</option>
                    <option value="WeTransfer">WeTransfer</option>
                    <option value="USB Drive">USB Drive</option>
                    <option value="Physical">Physical Delivery</option>
                    <option value="Email">Email</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            {{-- Due Date & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Due Date</label>
                    <input type="date" name="due_date"
                           class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                           :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
                </div>
                <div>
                    <label class="text-gray-400 text-xs mb-1 block">Status *</label>
                    <select name="status" required
                            class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                            :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'">
                        @foreach(['Pending', 'In Progress', 'Ready', 'Delivered', 'Approved'] as $status)
                        <option value="{{ $status }}" {{ $status === 'Pending' ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Drive Link --}}
            <div class="mb-4">
                <label class="text-gray-400 text-xs mb-1 block">Drive / Download Link</label>
                <input type="text" name="drive_link"
                       class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                       :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                       placeholder="https://drive.google.com/..."/>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="text-gray-400 text-xs mb-1 block">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-primary"
                          :style="dark ? 'background:#252840;color:#fff;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"
                          placeholder="Additional notes..."></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('deliverables.index') }}"
                   class="flex-1 text-center text-sm font-medium py-2 rounded-lg transition-colors"
                   :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-medium py-2 rounded-lg transition-colors">
                    Add Deliverable
                </button>
            </div>
        </form>
    </div>
</div>

@endsection