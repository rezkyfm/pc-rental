@extends('layouts.admin')

@section('title', 'Create New Rental')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.rentals.index') }}" class="text-slate-600 hover:text-slate-800 transition">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Rentals
            </div>
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New Rental</h1>
        <p class="mt-1 text-sm text-gray-500">Create a new PC rental for a customer.</p>
    </div>

    @livewire('rental-form', ['selectedPcId' => $selectedPcId ?? null, 'selectedDate' => $selectedDate ?? null])
@endsection