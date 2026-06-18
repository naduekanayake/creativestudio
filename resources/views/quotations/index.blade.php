@extends('layouts.app')

@section('title', 'Quotations')

@section('content')

<div x-data="quotationSearch()">

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Quotations</h1>
        <p class="text-gray-400 text-sm mt-0.5">Generate and manage professional quotations</p>
    </div>
    <a href="{{ route('quotations.create') }}"
       class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create Quotation
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-purple-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['total'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Quotations</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-green-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['accepted'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Accepted</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['sent'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Sent</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-gray-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['draft'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Draft</p>
    </div>
</div>

{{-- Quotations Table --}}
<div class="rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
    <div class="p-4 flex items-center justify-between" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
        <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">All Quotations</h3>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" x-model="search" placeholder="Search quotations..."
                   class="text-sm rounded-lg pl-9 pr-3 py-1.5 w-56 focus:outline-none focus:border-primary"
                   :style="dark ? 'background:#252840;color:#d1d5db;border:1px solid #2d3154' : 'background:#f9fafb;color:#111827;border:1px solid #e5e7eb'"/>
        </div>
    </div>

    <table class="w-full">
        <thead>
            <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                <th class="text-left px-4 py-3">QUOTATION</th>
                <th class="text-left px-4 py-3">CLIENT</th>
                <th class="text-left px-4 py-3">PROJECT/EVENT</th>
                <th class="text-left px-4 py-3">ISSUE DATE</th>
                <th class="text-left px-4 py-3">AMOUNT</th>
                <th class="text-left px-4 py-3">STATUS</th>
                <th class="text-left px-4 py-3">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotations as $quotation)
            <tr class="searchable-row"
                data-search="{{ strtolower($quotation->quotation_number . ' ' . $quotation->client->name . ' ' . $quotation->project_event . ' ' . $quotation->status) }}"
                x-show="matches('{{ strtolower($quotation->quotation_number . ' ' . $quotation->client->name . ' ' . $quotation->project_event . ' ' . $quotation->status) }}')"
                :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $quotation->quotation_number }}</p>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $quotation->client->name }}</p>
                </td>
                <td class="px-4 py-3 text-gray-400 text-sm">{{ $quotation->project_event ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-400 text-sm">{{ $quotation->issue_date->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($quotation->total_amount, 2) }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $quotation->status_color }}">
                        {{ $quotation->status }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('quotations.show', $quotation) }}"
                           class="text-gray-400 hover:text-primary transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('quotations.destroy', $quotation) }}"
                              onsubmit="return confirm('Delete this quotation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-400 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    No quotations yet. Create your first quotation!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- No search results --}}
    <div x-show="search.length > 0 && visibleCount() === 0" class="px-4 py-8 text-center text-gray-500 text-sm">
        No quotations match "<span x-text="search"></span>"
    </div>

    @if($quotations->hasPages())
    <div class="px-4 py-3" x-show="search.length === 0" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
        {{ $quotations->links() }}
    </div>
    @endif
</div>

</div>

<script>
function quotationSearch() {
    return {
        search: '',
        matches(text) {
            if (this.search.length === 0) return true;
            return text.includes(this.search.toLowerCase());
        },
        visibleCount() {
            if (this.search.length === 0) return 1;
            const rows = document.querySelectorAll('.searchable-row');
            let count = 0;
            rows.forEach(row => {
                if (row.dataset.search.includes(this.search.toLowerCase())) count++;
            });
            return count;
        }
    }
}
</script>

@endsection