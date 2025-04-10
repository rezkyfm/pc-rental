@extends('layouts.admin')

@section('title', 'Payment Management')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage payments, process refunds, and generate reports.</p>
        </div>
        <a href="{{ route('admin.payments.create') }}" class="bg-slate-800 text-white py-2 px-4 rounded-md hover:bg-slate-900 transition">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Record New Payment
            </div>
        </a>
    </div>

    @livewire('payment-table')
@endsection