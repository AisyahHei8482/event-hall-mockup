@extends('layouts.admin')

@section('title', 'Add Facility - Admin')
@section('page_title', 'Add Facility')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-3xl">
        <form method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data">
            @include('admin.facilities._form')
        </form>
    </div>
@endsection
