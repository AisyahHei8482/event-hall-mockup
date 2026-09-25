@extends('layouts.admin')

@section('title', 'New Seasonal Rate - Admin')
@section('page_title', 'New Seasonal Rate')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.seasonal-rates.store') }}">
            @include('admin.seasonal-rates._form')
        </form>
    </div>
@endsection
