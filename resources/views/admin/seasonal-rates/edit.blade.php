@extends('layouts.admin')

@section('title', 'Edit Seasonal Rate - Admin')
@section('page_title', 'Edit Seasonal Rate')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.seasonal-rates.update', $seasonalRate) }}">
            @include('admin.seasonal-rates._form')
        </form>
    </div>
@endsection
