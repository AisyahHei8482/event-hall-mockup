@extends('layouts.admin')

@section('title', 'New Package - Admin')
@section('page_title', 'New Package')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.packages.store') }}" enctype="multipart/form-data">
            @include('admin.packages._form')
        </form>
    </div>
@endsection
