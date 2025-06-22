@extends('layouts.admin')

@section('title', 'Payment Details')

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

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $payment->payment_number }} | 
                {{ $payment->payment_date->format('M d, Y H:i') }}
            </p>
        </div>
        
        <div class="mt-4 md:mt-0 flex space-x-2">
            @if($payment->type != 'refund' && $payment->status == 'pending')
                <form method="POST" action="{{ route('admin.payments.status', $payment) }}">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="bg-green-600 text-white py-2 px-3 rounded-md hover:bg-green-700 transition">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mark as Completed
                        </div>
                    </button>
                </form>
                
                <form method="POST" action="{{ route('admin.payments.status', $payment) }}">
                    @csrf
                    <input type="hidden" name="status" value="failed">
                    <button type="submit" class="bg-red-600 text-white py-2 px-3 rounded-md hover:bg-red-700 transition">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Mark as Failed
                        </div>
                    </button>
                </form>
            @endif
            
            @if($payment->type != 'refund' && $payment->status == 'completed' && $payment->amount > 0)
                <button type="button" x-data="{}" 
                    x-on:click="window.livewire.dispatch('openModal', { component: 'refund-payment-form', arguments: { paymentId: {{ $payment->id }} }})" 
                    class="bg-orange-600 text-white py-2 px-3 rounded-md hover:bg-orange-700 transition">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Process Refund
                    </div>
                </button>
            @endif
        </div>
    </div>

    <!-- Payment Receipt -->
    @livewire('payment-receipt', ['payment' => $payment])
@endsection