@props(['type' => 'button', 'icon' => null])

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-white text-indigo-700 border-2 border-indigo-100 shadow-md hover:shadow-lg px-4 py-2.5 font-semibold transition-all duration-150 hover:-translate-y-0.5 hover:border-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:ring-offset-1']) }}
>
    @if($icon)
        {!! $icon !!}
    @endif
    {{ $slot }}
</button>
