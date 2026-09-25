@props(['disabled' => false, 'icon' => null])

<div class="relative">
    @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
            <i class="{{ $icon }}"></i>
        </div>
    @endif
    
    <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full rounded-xl border-slate-200 shadow-sm focus:border-forest-500 focus:ring focus:ring-forest-500/20 transition-colors sm:text-sm px-4 py-2.5 ' . ($icon ? 'pl-10' : '')]) !!}>
        {{ $slot }}
    </select>
</div>
