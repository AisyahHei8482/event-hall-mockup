@extends('layouts.admin')

@section('title', 'New Promotion - Admin')
@section('page_title', 'New Promotion')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.promotions.store') }}" enctype="multipart/form-data">
            @include('admin.promotions._form')
        </form>
    </div>
@endsection
