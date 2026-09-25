@extends('layouts.management')
@section('title', 'Edit Quotation')
@section('page_title', 'Edit Quotation ' . $quotation->quote_number)

@section('content')
<div class="max-w-7xl mx-auto">
    <form method="POST" action="{{ route('management.quotations.update', $quotation) }}">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Items -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <h3 class="font-semibold text-slate-900 border-b border-slate-100 pb-3">Quotation Items</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-left">
                                    <th class="pb-2 font-medium text-slate-500">Description</th>
                                    <th class="pb-2 font-medium text-slate-500 text-center w-24">Qty</th>
                                    <th class="pb-2 font-medium text-slate-500 text-right w-32">Unit Price (RM)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($quotation->items as $index => $item)
                                <tr>
                                    <td class="py-3">
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                        <div class="font-medium text-slate-900">{{ $item->description }}</div>
                                        @if($item->unit)<div class="text-xs text-slate-500">per {{ $item->unit }}</div>@endif
                                    </td>
                                    <td class="py-3">
                                        <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" step="1"
                                               class="w-full text-center border border-slate-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                                    </td>
                                    <td class="py-3">
                                        <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" min="0" step="0.01"
                                               class="w-full text-right border border-slate-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-slate-500 italic mt-2">Note: Subtotal, Taxes, and Grand Total will be automatically recalculated upon saving.</p>
                </div>
            </div>

            <!-- Right Column: Settings & Notes -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <h3 class="font-semibold text-slate-900 border-b border-slate-100 pb-3">Price Override & Validation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Discount Amount (RM)</label>
                            <input type="number" name="discount_amount" value="{{ old('discount_amount', $quotation->discount_amount) }}"
                                   step="0.01" min="0" max="{{ $quotation->subtotal + $quotation->addons_total }}"
                                   class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Discount Reason</label>
                            <input type="text" name="discount_reason" value="{{ old('discount_reason', $quotation->discount_reason) }}"
                                   placeholder="e.g. Corporate rate"
                                   class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Valid Until</label>
                            <input type="date" name="valid_until" value="{{ old('valid_until', $quotation->valid_until?->format('Y-m-d')) }}"
                                   class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <h3 class="font-semibold text-slate-900 border-b border-slate-100 pb-3">Notes</h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Terms & Conditions</label>
                        <textarea name="terms_conditions" rows="3"
                                  class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none">{{ old('terms_conditions', $quotation->terms_conditions) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Internal Notes</label>
                        <textarea name="internal_notes" rows="2"
                                  class="w-full border border-slate-300 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none bg-amber-50">{{ old('internal_notes', $quotation->internal_notes) }}</textarea>
                    </div>
                </div>

                <div class="flex gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm justify-between items-center">
                    <a href="{{ route('management.quotations.show', $quotation) }}" class="text-slate-500 hover:text-slate-700 font-medium px-2 py-2 text-sm">Cancel</a>
                    <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-sm transition-colors">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
