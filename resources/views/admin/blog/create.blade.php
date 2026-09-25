@extends('layouts.admin')

@section('title', 'New Post - Admin')
@section('page_title', 'New Blog Post')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
            @include('admin.blog._form')
        </form>
    </div>
@endsection
