@php
    $active = request()->url() === $href;
@endphp

<a href="{{ $href }}"
   class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs transition-all
          {{ $active ? 'text-primary font-medium' : 'text-gray-500 hover:text-white' }}">
    <span class="w-1 h-1 rounded-full bg-current"></span>
    {{ $label }}
</a>