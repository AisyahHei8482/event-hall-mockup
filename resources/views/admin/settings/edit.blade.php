@extends('layouts.admin')

@section('title', 'Settings - Admin')
@section('page_title', 'Site Settings')

@section('content')
    <div class="max-w-2xl">
        <x-card class="p-8 border-slate-100 shadow-sm">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Resort Email</label>
                    <x-input type="email" name="resort_email" value="{{ old('resort_email', $settings['resort_email']) }}" />
                    @error('resort_email')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Resort Phone</label>
                    <x-input type="text" name="resort_phone" value="{{ old('resort_phone', $settings['resort_phone']) }}" />
                    @error('resort_phone')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Resort Address</label>
                    <textarea name="resort_address" rows="3"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-forest-500 focus:border-forest-500 transition-all font-medium text-sm">{{ old('resort_address', $settings['resort_address']) }}</textarea>
                    @error('resort_address')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Facebook URL</label>
                    <x-input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}" />
                    @error('facebook_url')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Instagram URL</label>
                    <x-input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" />
                    @error('instagram_url')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">WhatsApp Number</label>
                    <x-input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" />
                    @error('whatsapp_number')<p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <input type="hidden" name="maintenance_mode" value="0">
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1"
                        @checked(old('maintenance_mode', $settings['maintenance_mode']) == '1')
                        class="w-5 h-5 rounded border-slate-300 text-forest-600 focus:ring-forest-500 bg-white">
                    <label for="maintenance_mode" class="text-sm font-bold text-slate-900 cursor-pointer">Enable Maintenance Mode</label>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <x-button variant="primary" type="submit" class="w-full justify-center shadow-lg shadow-emerald-500/20 py-3">
                        Save Settings
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
