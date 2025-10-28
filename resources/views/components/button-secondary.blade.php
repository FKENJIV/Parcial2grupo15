@props(['type' => 'button', 'icon' => null])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-white text-sky-700 border-2 border-sky-200 shadow-md hover:shadow-lg px-4 py-2.5 font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:border-sky-300']) }}
>
    @if($icon)
        {!! $icon !!}
    @endif
    {{ $slot }}
</button>
