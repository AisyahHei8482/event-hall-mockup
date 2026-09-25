@props([
    'variant' => 'info', // success, warning, danger, info, neutral
    'pulse' => false,
])

@php
    $baseClasses = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium';
    
    $variantClasses = match ($variant) {
        'success' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
        'warning' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10',
        'info' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
        default => 'bg-slate-50 text-slate-600 ring-1 ring-inset ring-slate-500/10', // neutral
    };
    
    $dotClasses = match ($variant) {
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-blue-500',
        default => 'bg-slate-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
    @if($pulse)
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dotClasses }} opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 {{ $dotClasses }}"></span>
        </span>
    @endif
    {{ $slot }}
</span>
