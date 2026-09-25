@extends('layouts.admin')

@section('title', 'Edit Image - Admin')
@section('page_title', 'Edit Gallery Image')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.gallery.update', $image) }}" enctype="multipart/form-data">
            @include('admin.gallery._form')
        </form>
    </div>
@endsection
