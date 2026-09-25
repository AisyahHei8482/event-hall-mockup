@extends('layouts.admin')

@section('title', 'Blog - Admin')
@section('page_title', 'Blog Posts')

@section('content')
    <div class="flex justify-end mb-8">
        <a href="{{ route('admin.blog.create') }}" class="inline-block">
            <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> New Post
            </x-button>
        </a>
    </div>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Title</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Category</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Published</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $post->title }}</td>
                            <td class="px-6 py-4">
                                <span class="capitalize font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded-md">{{ str_replace('-', ' ', $post->category) }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600">{{ $post->published_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <x-badge variant="{{ $post->is_published ? 'emerald' : 'slate' }}" class="shadow-sm">
                                    {{ $post->is_published ? 'Published' : 'Draft' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.blog.edit', $post) }}"
                                       class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Delete this post?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-slate-500 font-medium">No posts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($posts->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $posts->links() }}</div>
        @endif
    </x-card>
@endsection
