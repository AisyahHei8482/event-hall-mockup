@extends('layouts.admin')

@section('title', 'New Add-on - Admin')
@section('page_title', 'New Add-on')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.addons.store') }}">
            @include('admin.addons._form')
        </form>
    </div>
@endsection
