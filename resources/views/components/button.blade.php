@props([
    'variant' => 'primary', // primary, secondary, outline, danger, ghost
    'size' => 'md', // sm, md, lg
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-7 py-3 text-base',
        default => 'px-5 py-2.5 text-sm', // md
    };
    
    $variantClasses = match ($variant) {
        'primary' => 'bg-forest-600 text-white hover:bg-forest-700 shadow-md shadow-forest-500/20 focus:ring-forest-500',
        'secondary' => 'bg-forest-100 text-forest-800 hover:bg-forest-200 focus:ring-forest-500',
        'amber' => 'bg-amber-500 text-white hover:bg-amber-600 shadow-md shadow-amber-500/20 focus:ring-amber-500',
        'outline' => 'border border-forest-200 text-forest-700 hover:bg-forest-50 focus:ring-forest-500',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 shadow-md shadow-red-500/20 focus:ring-red-500',
        'ghost' => 'text-forest-600 hover:bg-forest-50 focus:ring-forest-500',
        'white' => 'bg-white text-slate-800 hover:bg-slate-50 shadow-sm focus:ring-slate-500',
        default => 'bg-forest-600 text-white hover:bg-forest-700 focus:ring-forest-500',
    };
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => "$baseClasses $sizeClasses $variantClasses"]) }}>
    {{ $slot }}
</button>
