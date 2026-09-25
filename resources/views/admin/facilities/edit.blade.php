@extends('layouts.admin')

@section('title', 'Edit Facility - Admin')
@section('page_title', 'Edit Facility')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.facilities.update', $facility) }}" enctype="multipart/form-data">
            @include('admin.facilities._form')
        </form>
    </div>
@endsection
