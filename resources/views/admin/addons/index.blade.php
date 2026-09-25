@extends('layouts.admin')

@section('title', 'Add-ons - Admin')
@section('page_title', 'Add-ons')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.addons.create') }}" class="bg-forest-600 hover:bg-forest-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">
            <i class="fa-solid fa-plus mr-1"></i> New Add-on
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-forest-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-forest-50 text-forest-600 text-left">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Facility</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-forest-100">
                @foreach ($addons as $addon)
                    <tr>
                        <td class="px-4 py-3 font-medium text-forest-900">{{ $addon->name }}</td>
                        <td class="px-4 py-3 text-forest-600">{{ $addon->facility->name ?? 'All Facilities' }}</td>
                        <td class="px-4 py-3 text-forest-600">RM {{ number_format($addon->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $addon->is_active ? 'bg-forest-100 text-forest-700' : 'bg-red-100 text-red-700' }}">
                                {{ $addon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.addons.edit', $addon) }}" class="text-forest-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.addons.destroy', $addon) }}" class="inline" onsubmit="return confirm('Delete this add-on?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $addons->links() }}</div>
@endsection
