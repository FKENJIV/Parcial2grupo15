@props(['type' => 'button', 'icon' => null])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-sky-500 text-white border border-sky-600 shadow-lg hover:shadow-xl px-4 py-2.5 font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:bg-sky-600']) }}
>
    @if($icon)
        {!! $icon !!}
    @endif
    {{ $slot }}
</button>
