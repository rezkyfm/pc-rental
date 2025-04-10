@extends('layouts.admin')

@section('title', 'Rentals Management')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rentals Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage PC rentals, track status, and process payments.</p>
        </div>
        <a href="{{ route('admin.rentals.create') }}" class="bg-slate-800 text-white py-2 px-4 rounded-md hover:bg-slate-900 transition">
            Create New Rental
        </a>
    </div>

    @livewire('rental-table')
@endsection