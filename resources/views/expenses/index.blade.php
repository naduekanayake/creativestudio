@extends('layouts.app')

@section('title', 'Expenses')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">Expenses</h1>
        <p class="text-gray-400 text-sm mt-0.5">Track business expenses and costs</p>
    </div>
    <a href="{{ route('expenses.create') }}"
       class="bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Expense
    </a>
</div>

@if(session('success'))
<div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-red-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-red-400">Rs. {{ number_format($stats['total']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Expenses</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-orange-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-orange-400">Rs. {{ number_format($stats['this_month']) }}</p>
        <p class="text-gray-400 text-xs mt-0.5">This Month</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-blue-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <p class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $stats['count'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Records</p>
    </div>
    <div class="rounded-xl p-4" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="w-9 h-9 bg-yellow-500/20 rounded-lg flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Pending Approval</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-4">

    {{-- Category Breakdown --}}
    <div class="rounded-xl p-5" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <h3 class="font-semibold mb-4 text-sm" :class="dark ? 'text-white' : 'text-gray-900'">By Category</h3>
        <div class="space-y-3">
            @php $maxCat = $categoryTotals->max('total') ?: 1; @endphp
            @forelse($categoryTotals as $cat)
            @php $barWidth = round(($cat->total / $maxCat) * 100); @endphp
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-400">{{ $cat->category }}</span>
                    <span :class="dark ? 'text-white' : 'text-gray-900'">Rs. {{ number_format($cat->total) }}</span>
                </div>
                <div class="h-1.5 rounded-full" :style="dark ? 'background:#252840' : 'background:#f3f4f6'">
                    <div class="h-1.5 rounded-full bg-red-400" style="width: {{ $barWidth }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-sm">No expenses yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Expenses Table --}}
    <div class="col-span-2 rounded-xl" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">
        <div class="p-4" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
            <h3 class="font-semibold" :class="dark ? 'text-white' : 'text-gray-900'">All Expenses</h3>
        </div>
        <table class="w-full">
            <thead>
                <tr class="text-gray-500 text-xs" :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #e5e7eb'">
                    <th class="text-left px-4 py-3">TITLE</th>
                    <th class="text-left px-4 py-3">CATEGORY</th>
                    <th class="text-left px-4 py-3">DATE</th>
                    <th class="text-left px-4 py-3">METHOD</th>
                    <th class="text-right px-4 py-3">AMOUNT</th>
                    <th class="text-left px-4 py-3">STATUS</th>
                    <th class="text-left px-4 py-3">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr :style="dark ? 'border-bottom:1px solid #252840' : 'border-bottom:1px solid #f3f4f6'">
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $expense->title }}</p>
                        @if($expense->receipt_number)
                        <p class="text-gray-500 text-xs">{{ $expense->receipt_number }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $expense->category_color }}">{{ $expense->category }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $expense->expense_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $expense->payment_method }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-400 text-right">Rs. {{ number_format($expense->amount, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $expense->status_color }}">{{ $expense->status }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('expenses.show', $expense) }}"
                               class="text-gray-400 hover:text-primary transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('expenses.edit', $expense) }}"
                               class="text-gray-400 hover:text-primary transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                  onsubmit="return confirm('Delete this expense?')">
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
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">No expenses yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($expenses->hasPages())
        <div class="px-4 py-3" :style="dark ? 'border-top:1px solid #252840' : 'border-top:1px solid #e5e7eb'">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>

@endsection