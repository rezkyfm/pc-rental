@extends('layouts.app')

@section('title', 'Rental Details')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Rental Details
            </h1>
            <p class="mt-2 text-gray-600">Invoice #{{ $rental->invoice_number }}</p>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="md:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Rental Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">PC Details</h3>
                                <div class="mt-2 flex items-center">
                                    <div
                                        class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-base font-medium text-gray-900">{{ $rental->pc->name }}</div>
                                        <div class="text-sm text-gray-500">Hourly Rate: Rp
                                            {{ number_format($rental->pc->rental_price_hourly, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Rental Status</h3>
                                <div class="mt-2">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                            {{ $rental->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $rental->status === 'pending' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $rental->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $rental->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Rental Period</h3>
                                <div class="mt-2">
                                    <div class="text-base font-medium text-gray-900">
                                        {{ $rental->start_time->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $rental->start_time->format('H:i') }} - {{ $rental->end_time->format('H:i') }}
                                        ({{ $rental->start_time->diffInHours($rental->end_time) }} hours)
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    {{ $rental->status === 'completed' ? 'Actual Return' : 'Expected Return' }}
                                </h3>
                                <div class="mt-2">
                                    @if($rental->status === 'completed' && $rental->actual_return_time)
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $rental->actual_return_time->format('M d, Y H:i') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Total Duration: {{ $rental->start_time->diffInHours($rental->actual_return_time) }}
                                            hours
                                        </div>
                                    @elseif($rental->status === 'active' || $rental->status === 'pending')
                                        <div class="text-base font-medium text-gray-900">
                                            {{ $rental->end_time->format('M d, Y H:i') }}
                                        </div>
                                        @if($rental->status === 'active' && now()->isAfter($rental->end_time))
                                            <div class="text-sm text-red-600 font-medium">
                                                Overdue by {{ now()->diffInHours($rental->end_time) }} hours
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-sm text-gray-500">Not applicable</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 mt-6 pt-6">
                            <h3 class="text-sm font-medium text-gray-500">PC Specifications</h3>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($rental->pc->components->groupBy('category.name') as $categoryName => $components)
                                    <div class="bg-gray-50 p-3 rounded-md">
                                        <h4 class="font-medium text-gray-900 text-sm">{{ $categoryName }}</h4>
                                        <ul class="mt-1 space-y-1">
                                            @foreach($components as $component)
                                                <li class="text-sm text-gray-600">
                                                    {{ $component->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Payment Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-medium text-gray-500">Total Price</div>
                                <div class="mt-1 text-2xl font-bold text-gray-900">Rp
                                    {{ number_format($rental->total_price, 0, ',', '.') }}</div>
                            </div>

                            <div>
                                <div class="text-sm font-medium text-gray-500">Deposit Amount</div>
                                <div class="mt-1 text-base font-medium text-gray-900">Rp
                                    {{ number_format($rental->deposit_amount, 0, ',', '.') }}</div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="text-sm font-medium text-gray-500">Payments</div>
                                <div class="mt-2 space-y-3">
                                    @foreach($rental->payments as $payment)
                                        <div class="bg-gray-50 p-3 rounded-md">
                                            <div class="flex justify-between">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ ucfirst($payment->type) }} Payment
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i') : 'N/A' }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ ucfirst($payment->method) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                                        {{ $payment->status === 'pending' ? 'bg-blue-100 text-blue-800' : '' }}
                                                        {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}
                                                        {{ $payment->status === 'refunded' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($rental->status === 'active')
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="text-sm font-medium text-gray-500 mb-2">Actions</div>
                                    <a href="#"
                                        class="block w-full text-center bg-red-600 text-white py-2 px-4 rounded-md font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                        Request to Cancel Rental
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection