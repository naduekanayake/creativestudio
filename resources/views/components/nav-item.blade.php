@php
    $active = request()->url() === $href;
@endphp

<a href="{{ $href }}"
   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium transition-all duration-150
          {{ $active 
             ? 'bg-primary text-white' 
             : 'text-gray-400 hover:text-white hover:bg-dark-700' }}">

    {{-- Icon --}}
    <span class="w-4 h-4 flex-shrink-0">
        @include('components.icons.' . $icon)
    </span>

    <span>{{ $label }}</span>
</a>