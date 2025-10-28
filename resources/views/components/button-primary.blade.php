@props(['type' => 'button', 'icon' => null])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 text-white border border-indigo-700 shadow-lg hover:shadow-xl px-4 py-2.5 font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-1']) }}
>
    @if($icon)
        {!! $icon !!}
    @endif
    {{ $slot }}
</button>
