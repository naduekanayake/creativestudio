<div x-data="{ open: false }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white hover:bg-dark-700 transition-all">
        <div class="flex items-center gap-2.5">
            <span class="w-4 h-4 flex-shrink-0">
                @include('components.icons.' . $icon)
            </span>
            <span>{{ $label }}</span>
        </div>
        <svg class="w-3 h-3 transition-transform duration-200" :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="open" class="ml-6 mt-0.5 space-y-0.5">
        {{ $slot }}
    </div>
</div>