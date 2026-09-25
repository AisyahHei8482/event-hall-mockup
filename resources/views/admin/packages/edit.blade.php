@extends('layouts.admin')

@section('title', 'Edit Package - Admin')
@section('page_title', 'Edit Package')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}" enctype="multipart/form-data">
            @include('admin.packages._form')
        </form>
    </div>
@endsection
