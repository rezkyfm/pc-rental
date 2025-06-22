@extends('layouts.admin')

@section('title', 'Rental Details')

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

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Invoice #{{ $rental->invoice_number }}</h2>
                    <p class="text-sm text-gray-600">Created on {{ $rental->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    @if($rental->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @elseif($rental->status == 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        @if($rental->isOverdue)
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Overdue
                            </span>
                        @endif
                    @elseif($rental->status == 'completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Completed
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Cancelled
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-10 w-10 bg-slate-200 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-slate-600">{{ substr($rental->user->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-lg font-medium text-gray-900">{{ $rental->user->name }}</p>
                                <p class="text-gray-500">{{ $rental->user->email }}</p>
                            </div>
                        </div>
                        
                        @if($rental->user->phone)
                            <div class="mt-2">
                                <p class="text-sm font-medium text-gray-500">Phone</p>
                                <p>{{ $rental->user->phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">PC Information</h3>
                    
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <p class="text-lg font-medium text-gray-900">{{ $rental->pc->name }}</p>
                        <p class="text-gray-500">Code: {{ $rental->pc->code }}</p>
                        
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Hourly Rate</p>
                                <p>Rp {{ number_format($rental->pc->rental_price_hourly, 0, ',', '.') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Components</p>
                                <p>{{ $rental->pc->components->count() }} items</p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('admin.pcs.show', $rental->pc) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View PC Details →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rental Details -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Details</h3>
                
                <div class="bg-white p-4 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Start Time</p>
                            <p>{{ $rental->start_time->format('M d, Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Scheduled End Time</p>
                            <p>{{ $rental->end_time->format('M d, Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                @if($rental->status === 'completed')
                                    Actual Return Time
                                @else
                                    Rental Duration
                                @endif
                            </p>
                            @if($rental->status === 'completed' && $rental->actual_return_time)
                                <p>{{ $rental->actual_return_time->format('M d, Y H:i') }}</p>
                            @else
                                <p>{{ $rental->getDurationInHoursAttribute() }} hours</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Deposit Amount</p>
                                <p class="font-medium">Rp {{ number_format($rental->deposit_amount, 0, ',', '.') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Amount</p>
                                <p class="font-medium">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    @if($rental->status === 'completed')
                                        Balance
                                    @else
                                        Estimated Balance
                                    @endif
                                </p>
                                <p class="font-medium">
                                    Rp {{ number_format(max(0, $rental->total_price - $rental->deposit_amount), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($rental->notes)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-medium text-gray-500">Notes</p>
                            <p class="mt-1 text-gray-600">{{ $rental->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Payment History -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
                
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($rental->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $payment->payment_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->payment_date->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst($payment->type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $payment->method)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($payment->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($payment->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Failed
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No payment records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-8 flex flex-wrap justify-end gap-4">
                <!-- Invoice Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.rentals.invoice.download', $rental) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Invoice
                    </a>
                    <a href="{{ route('admin.rentals.invoice.view', $rental) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Invoice
                    </a>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    @if($rental->status === 'active')
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            x-data="{}" 
                            x-on:click="window.livewire.dispatch('openModal', { component: 'complete-rental-form', arguments: { rentalId: {{ $rental->id }} }})">
                            Complete Rental
                        </button>
                    @endif
                    
                    @if(in_array($rental->status, ['pending', 'active']))
                        <form method="POST" action="{{ route('admin.rentals.cancel', $rental) }}" onsubmit="return confirm('Are you sure you want to cancel this rental?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Cancel Rental
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection