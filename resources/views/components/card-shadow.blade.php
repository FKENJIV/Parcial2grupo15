@props(['title' => null, 'icon' => null])

<div class="relative">
    <!-- Soft decorative blur -->
    <div aria-hidden class="absolute -inset-6 rounded-2xl bg-sky-100 opacity-20 blur-[40px] pointer-events-none z-0"></div>
    <div aria-hidden class="absolute -inset-2 rounded-2xl bg-black opacity-4 blur-[24px] pointer-events-none z-0"></div>
    
    <div class="relative p-1 rounded-xl bg-gradient-to-r from-sky-50 to-white z-10">
        <div class="relative bg-white rounded-xl shadow-[0_30px_80px_rgba(2,6,23,0.12)] border-2 border-sky-200 ring-1 ring-sky-50 overflow-hidden transform transition-all hover:-translate-y-0.5 hover:shadow-[0_40px_100px_rgba(2,6,23,0.16)]">
            @if($title || $icon)
            <div class="px-6 py-4 border-b border-sky-100 bg-gradient-to-r from-sky-50 to-white">
                <div class="flex items-center gap-3">
                    @if($icon)
                        <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white shadow-md">
                            {!! $icon !!}
                        </div>
                    @endif
                    @if($title)
                        <h3 class="text-lg font-semibold text-sky-700">{{ $title }}</h3>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="p-6 relative z-10">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
