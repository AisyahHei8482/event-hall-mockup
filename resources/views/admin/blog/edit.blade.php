@extends('layouts.admin')

@section('title', 'Edit Post - Admin')
@section('page_title', 'Edit Blog Post')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.blog.update', $post) }}" enctype="multipart/form-data">
            @include('admin.blog._form')
        </form>
    </div>
@endsection
