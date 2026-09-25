@extends('layouts.admin')

@section('title', 'Edit Promotion - Admin')
@section('page_title', 'Edit Promotion')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}" enctype="multipart/form-data">
            @include('admin.promotions._form')
        </form>
    </div>
@endsection
