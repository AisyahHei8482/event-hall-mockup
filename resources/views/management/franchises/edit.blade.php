@extends('layouts.management')
@section('title', 'Edit Franchise')
@section('page_title', 'Edit Franchise')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('management.franchises.update', $franchise) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        <x-card class="p-6 border-slate-100 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3 mb-5">Franchise Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Franchise Name <span class="text-red-500">*</span></label>
                    <x-input type="text" name="name" value="{{ old('name', $franchise->name) }}" required />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Code <span class="text-red-500">*</span></label>
                    <x-input type="text" name="code" value="{{ old('code', $franchise->code) }}" required class="font-mono uppercase" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Company Name</label>
                    <x-input type="text" name="company_name" value="{{ old('company_name', $franchise->company_name) }}" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Address</label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all font-medium text-sm resize-none">{{ old('address', $franchise->address) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Contact Person</label>
                    <x-input type="text" name="contact_person" value="{{ old('contact_person', $franchise->contact_person) }}" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                    <x-input type="text" name="phone" value="{{ old('phone', $franchise->phone) }}" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email</label>
                    <x-input type="email" name="email" value="{{ old('email', $franchise->email) }}" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Status</label>
                    <x-select name="status">
                        <option value="active" @selected(old('status',$franchise->status)==='active')>Active</option>
                        <option value="inactive" @selected(old('status',$franchise->status)==='inactive')>Inactive</option>
                    </x-select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Logo</label>
                    @if($franchise->logo)
                    <div class="mb-3 relative group rounded-xl overflow-hidden w-20 h-20 border border-slate-200 bg-slate-50">
                        <img src="{{ asset('storage/'.$franchise->logo) }}" class="w-full h-full object-contain p-2">
                        <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                    </div>
                    @endif
                    <x-input type="file" name="logo" accept="image/*" class="py-2.5" />
                </div>
            </div>
        </x-card>
        <div class="flex items-center gap-4 pt-2">
            <x-button type="submit" variant="primary" class="px-8 shadow-lg shadow-brand-500/20">
                Save Changes
            </x-button>
            <a href="{{ route('management.franchises.show', $franchise) }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
