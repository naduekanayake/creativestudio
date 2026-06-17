@extends('layouts.app')

@section('title', 'Expense Details')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('expenses.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold" :class="dark ? 'text-white' : 'text-gray-900'">{{ $expense->title }}</h1>
                <p class="text-gray-400 text-sm">Expense Details</p>
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs {{ $expense->status_color }}">{{ $expense->status }}</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('expenses.edit', $expense) }}"
               class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2"
               :class="dark ? 'bg-dark-700 hover:bg-dark-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                  onsubmit="return confirm('Delete this expense?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2 bg-red-500/20 text-red-400 hover:bg-red-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="rounded-xl p-6" :style="dark ? 'background:#1a1d2e;border:1px solid #252840' : 'background:#fff;border:1px solid #e5e7eb'">

        {{-- Amount --}}
        <div class="text-center py-6 mb-6" style="border-bottom:2px solid #7C3AED">
            <p class="text-gray-400 text-xs mb-2 tracking-widest">AMOUNT</p>
            <p class="text-4xl font-bold text-red-400">Rs. {{ number_format($expense->amount, 2) }}</p>
        </div>

        {{-- Details --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-gray-400 text-xs mb-1">CATEGORY</p>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $expense->category_color }}">{{ $expense->category }}</span>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">DATE</p>
                <p class="text-sm font-medium" :class="dark ? 'text-white' : 'text-gray-900'">{{ $expense->expense_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">PAYMENT METHOD</p>
                <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $expense->payment_method }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">STATUS</p>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $expense->status_color }}">{{ $expense->status }}</span>
            </div>
            @if($expense->receipt_number)
            <div>
                <p class="text-gray-400 text-xs mb-1">RECEIPT NUMBER</p>
                <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $expense->receipt_number }}</p>
            </div>
            @endif
            <div>
                <p class="text-gray-400 text-xs mb-1">RECORDED ON</p>
                <p class="text-sm text-gray-400">{{ $expense->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        @if($expense->description)
        <div class="mb-4 p-3 rounded-lg" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
            <p class="text-gray-400 text-xs mb-1">DESCRIPTION</p>
            <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $expense->description }}</p>
        </div>
        @endif

        @if($expense->notes)
        <div class="p-3 rounded-lg" :style="dark ? 'background:#252840' : 'background:#f9fafb'">
            <p class="text-gray-400 text-xs mb-1">NOTES</p>
            <p class="text-sm" :class="dark ? 'text-gray-300' : 'text-gray-700'">{{ $expense->notes }}</p>
        </div>
        @endif
    </div>
</div>

@endsection