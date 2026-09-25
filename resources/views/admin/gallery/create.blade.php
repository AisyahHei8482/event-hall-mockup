@extends('layouts.admin')

@section('title', 'Upload Image - Admin')
@section('page_title', 'Upload Gallery Image')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
            @include('admin.gallery._form')
        </form>
    </div>
@endsection
