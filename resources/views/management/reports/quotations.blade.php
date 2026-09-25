@extends('layouts.management')
@section('title', 'Quotations Report')
@section('page_title', 'Quotations Report')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-3 items-end flex-wrap">
        <div><label class="block text-xs font-medium text-slate-500 mb-1">From</label><input type="date" name="from" value="{{ $fromDate }}" class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"></div>
        <div><label class="block text-xs font-medium text-slate-500 mb-1">To</label><input type="date" name="to" value="{{ $toDate }}" class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"></div>
        <button type="submit" class="bg-brand-500 text-white px-5 py-2 rounded-xl text-sm font-medium">Apply</button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @foreach(['generated'=>'Generated','accepted'=>'Accepted','rejected'=>'Rejected','converted'=>'Converted'] as $k=>$l)
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="text-xs text-slate-400 mb-1">{{ $l }}</div>
        <div class="text-3xl font-bold text-slate-900">{{ $stats[$k] }}</div>
    </div>
    @endforeach
    <div class="md:col-span-4 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="text-sm text-slate-500">Conversion Rate: <span class="text-2xl font-bold text-brand-600">{{ $stats['conversion_rate'] }}%</span></div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-900">All Quotations</h3></div>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Quote #</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600">Guest</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600 hidden md:table-cell">Hall</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600">Status</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($recent as $q)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3"><a href="{{ route('management.quotations.show', $q) }}" class="font-mono text-xs text-brand-600 hover:underline">{{ $q->quote_number }}</a></td>
                <td class="px-4 py-3 font-medium">{{ $q->guest_name }}</td>
                <td class="px-4 py-3 text-slate-600 hidden md:table-cell">{{ $q->eventHall->name ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ match($q->status){ 'accepted','converted' => 'bg-emerald-100 text-emerald-700', 'rejected','expired' => 'bg-red-100 text-red-700', default => 'bg-blue-100 text-blue-700' } }}">{{ ucfirst($q->status) }}</span></td>
                <td class="px-5 py-3 text-right font-semibold">RM {{ number_format($q->total_amount,2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No quotations.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($recent->hasPages())<div class="px-5 py-4 border-t border-slate-100">{{ $recent->links() }}</div>@endif
</div>
@endsection
