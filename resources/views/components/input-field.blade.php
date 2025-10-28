@props(['label', 'name', 'type' => 'text', 'required' => false, 'placeholder' => '', 'value' => ''])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative rounded-lg shadow-md">
        <input 
            id="{{ $name }}" 
            name="{{ $name }}" 
            type="{{ $type }}"
            @if($required) required @endif
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'block w-full px-4 py-2.5 bg-white border-2 border-indigo-100 rounded-lg text-indigo-800 placeholder-indigo-300 shadow focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 transition-all']) }}
        />
    </div>
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
