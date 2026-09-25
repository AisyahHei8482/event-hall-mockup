@props([
    'variant' => 'info', // success, warning, danger, info
    'title' => null,
])

@php
    $baseClasses = 'p-4 rounded-xl border flex gap-3 shadow-sm';
    
    $variantClasses = match ($variant) {
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'danger' => 'bg-red-50 border-red-200 text-red-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        default => 'bg-slate-50 border-slate-200 text-slate-800',
    };
    
    $icon = match ($variant) {
        'success' => 'fa-solid fa-circle-check text-emerald-500',
        'warning' => 'fa-solid fa-triangle-exclamation text-amber-500',
        'danger' => 'fa-solid fa-circle-xmark text-red-500',
        'info' => 'fa-solid fa-circle-info text-blue-500',
        default => 'fa-solid fa-circle-info text-slate-500',
    };
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }} role="alert">
    <div class="shrink-0 mt-0.5">
        <i class="{{ $icon }} text-lg"></i>
    </div>
    <div class="flex-1">
        @if($title)
            <h3 class="text-sm font-semibold mb-1">{{ $title }}</h3>
        @endif
        <div class="text-sm opacity-90 leading-relaxed">
            {{ $slot }}
        </div>
    </div>
</div>
