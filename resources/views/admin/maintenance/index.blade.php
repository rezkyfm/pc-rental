@extends('layouts.admin')

@section('title', 'Maintenance Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Maintenance Management</h1>
            <a href="{{ route('admin.maintenance.create') }}" 
                class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Maintenance
            </a>
        </div>

        <div class="mt-6">
            @livewire('maintenance-table')
        </div>
    </div>
</div>
@endsection