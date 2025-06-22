<div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl mx-auto" id="payment-receipt">
    <!-- Header Section -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $payment->type === 'refund' ? 'REFUND RECEIPT' : 'PAYMENT RECEIPT' }}
            </h1>
            <p class="text-gray-600 mt-1">{{ $payment->payment_number }}</p>
            <p class="text-gray-600 mt-1">Date: {{ $payment->payment_date->format('F d, Y H:i') }}</p>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-gray-900">{{ $company['name'] }}</h2>
            <p class="text-gray-600">{{ $company['address'] }}</p>
            <p class="text-gray-600">Phone: {{ $company['phone'] }}</p>
            <p class="text-gray-600">Email: {{ $company['email'] }}</p>
        </div>
    </div>
    
    <!-- Customer & Rental Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="font-bold text-gray-900 mb-2">Customer Information</h3>
            <p class="text-gray-800">{{ $payment->rental->user->name }}</p>
            <p class="text-gray-600">{{ $payment->rental->user->email }}</p>
            @if($payment->rental->user->phone)
                <p class="text-gray-600">Phone: {{ $payment->rental->user->phone }}</p>
            @endif
        </div>
        
        <div class="border border-gray-200 rounded-lg p-4">
            <h3 class="font-bold text-gray-900 mb-2">Rental Information</h3>
            <p class="text-gray-800">Rental #: {{ $payment->rental->invoice_number }}</p>
            <p class="text-gray-600">PC: {{ $payment->rental->pc->name }} ({{ $payment->rental->pc->code }})</p>
            <p class="text-gray-600">Period: 
                {{ $payment->rental->start_time->format('M d, Y H:i') }} - 
                {{ $payment->rental->end_time->format('M d, Y H:i') }}
            </p>
        </div>
    </div>
    
    <!-- Payment Details -->
    <div class="border border-gray-200 rounded-lg p-4 mb-8">
        <h3 class="font-bold text-gray-900 mb-2">Payment Details</h3>
        <div class="overflow-hidden rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($payment->type === 'deposit')
                                Deposit payment for PC rental
                            @elseif($payment->type === 'rental')
                                Final payment for PC rental
                            @elseif($payment->type === 'extra')
                                Extra charges for PC rental
                            @elseif($payment->type === 'refund')
                                Refund for payment {{ str_replace('Refund for payment #', '', $payment->notes) }}
                            @endif
                            @if($payment->notes && $payment->type !== 'refund')
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->notes }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            @if($payment->type === 'deposit')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Deposit
                                </span>
                            @elseif($payment->type === 'rental')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Rental
                                </span>
                            @elseif($payment->type === 'extra')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Extra
                                </span>
                            @elseif($payment->type === 'refund')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Refund
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            {{ ucwords(str_replace('_', ' ', $payment->method)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $payment->amount >= 0 ? 'text-green-600 font-medium' : 'text-red-600 font-medium' }}">
                            {{ $payment->amount >= 0 ? '' : '- ' }}Rp {{ number_format(abs($payment->amount), 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Total</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right {{ $payment->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $payment->amount >= 0 ? '' : '- ' }}Rp {{ number_format(abs($payment->amount), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Payment Status -->
    <div class="border border-gray-200 rounded-lg p-4 mb-8">
        <h3 class="font-bold text-gray-900 mb-2">Payment Status</h3>
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-700">Status:
                    @if($payment->status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Completed
                        </span>
                    @elseif($payment->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @elseif($payment->status === 'failed')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Failed
                        </span>
                    @elseif($payment->status === 'refunded')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Refunded
                        </span>
                    @endif
                </p>
            </div>
            <div>
                @if($payment->status === 'completed')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                @elseif($payment->status === 'refunded')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Footer Section -->
    <div class="text-center text-gray-500 text-sm mt-8 pt-8 border-t border-gray-200">
        <p>This is a computer-generated receipt and does not require a signature.</p>
        <p class="mt-1">{{ $company['name'] }} | {{ $company['tax_id'] }}</p>
        <p class="mt-1">{{ $company['website'] }}</p>
    </div>
    
    <!-- Print Button -->
    <div class="mt-6 text-center">
        <button onclick="window.print()" class="bg-slate-600 text-white px-4 py-2 rounded hover:bg-slate-700 transition print:hidden">
            Print Receipt
        </button>
    </div>
    
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #payment-receipt, #payment-receipt * {
                visibility: visible;
            }
            #payment-receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</div>