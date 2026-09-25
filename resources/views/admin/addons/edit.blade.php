@extends('layouts.admin')

@section('title', 'Edit Add-on - Admin')
@section('page_title', 'Edit Add-on')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.addons.update', $addon) }}">
            @include('admin.addons._form')
        </form>
    </div>
@endsection
