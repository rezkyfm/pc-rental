@extends('layouts.admin')

@section('title', 'Record Payment')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.payments.index') }}" class="text-slate-600 hover:text-slate-800 transition">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Payments
            </div>
        </a>
    </div>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Record New Payment</h1>
        <p class="mt-1 text-sm text-gray-500">Record a payment for a rental.</p>
    </div>

    @if(isset($rental))
        @livewire('payment-form', ['rental' => $rental])
    @else
        @livewire('payment-form')
    @endif
@endsection