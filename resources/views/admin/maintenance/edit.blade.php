@extends('layouts.admin')

@section('title', 'Edit Maintenance')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
            <a href="{{ route('admin.maintenance.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Maintenance</h1>
        </div>

        <div class="mt-6">
            @livewire('maintenance-form', ['maintenance' => $maintenance])
        </div>
    </div>
</div>
@endsection