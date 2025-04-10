<div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Filters Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative rounded-md border">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                            class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md px-3 py-2"
                            placeholder="Search payments...">
                    </div>
                </div>
                
                <div>
                    <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                    <select id="typeFilter" wire:model.live="typeFilter"
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Types</option>
                        <option value="deposit">Deposit</option>
                        <option value="rental">Rental</option>
                        <option value="extra">Extra Charges</option>
                        <option value="refund">Refund</option>
                    </select>
                </div>
                
                <div>
                    <label for="methodFilter" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select id="methodFilter" wire:model.live="methodFilter"
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Methods</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                </div>
                
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="statusFilter" wire:model.live="statusFilter"
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                
                <div>
                    <label for="dateRangeStart" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" wire:model.live="dateRangeStart" id="dateRangeStart"
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                </div>
                
                <div>
                    <label for="dateRangeEnd" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" wire:model.live="dateRangeEnd" id="dateRangeEnd"
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                </div>
            </div>
            
            <div class="mt-6 flex justify-between items-center">
                <div>
                    <span class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $payments->firstItem() ?? 0 }}</span>
                        to
                        <span class="font-medium">{{ $payments->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-medium">{{ $payments->total() }}</span>
                        payments
                    </span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-700">Items per page:</span>
                    <select wire:model.live="perPage"
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Totals Summary -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-base font-medium text-gray-900 mb-4">Financial Summary (Current Filter)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Total</p>
                    <p class="text-lg font-bold {{ $totals['total'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($totals['total'], 0, ',', '.') }}
                    </p>
                </div>
                
                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Deposits</p>
                    <p class="text-lg font-bold text-blue-600">
                        Rp {{ number_format($totals['deposit'], 0, ',', '.') }}
                    </p>
                </div>
                
                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Rental Payments</p>
                    <p class="text-lg font-bold text-green-600">
                        Rp {{ number_format($totals['rental'], 0, ',', '.') }}
                    </p>
                </div>
                
                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Extra Charges</p>
                    <p class="text-lg font-bold text-yellow-600">
                        Rp {{ number_format($totals['extra'], 0, ',', '.') }}
                    </p>
                </div>
                
                <div class="bg-white p-3 rounded border border-gray-200 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">Refunds</p>
                    <p class="text-lg font-bold text-red-600">
                        Rp {{ number_format($totals['refund'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('payment_date')">
                            <div class="flex items-center">
                                Date
                                @if($sortField === 'payment_date')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('payment_number')">
                            <div class="flex items-center">
                                Payment #
                                @if($sortField === 'payment_number')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rental & Customer
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('type')">
                            <div class="flex items-center">
                                Type
                                @if($sortField === 'type')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('method')">
                            <div class="flex items-center">
                                Method
                                @if($sortField === 'method')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('amount')">
                            <div class="flex items-center">
                                Amount
                                @if($sortField === 'amount')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                            wire:click="sortBy('status')">
                            <div class="flex items-center">
                                Status
                                @if($sortField === 'status')
                                    @if($sortDirection === 'asc')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->payment_date->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $payment->payment_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>
                                    <span class="block font-medium text-gray-900">{{ $payment->rental->invoice_number }}</span>
                                    <span class="block">{{ $payment->rental->user->name }}</span>
                                    <span class="block text-xs">{{ $payment->rental->pc->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($payment->type == 'deposit')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Deposit
                                    </span>
                                @elseif($payment->type == 'rental')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Rental
                                    </span>
                                @elseif($payment->type == 'extra')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Extra
                                    </span>
                                @elseif($payment->type == 'refund')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Refund
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucwords(str_replace('_', ' ', $payment->method)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $payment->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Rp {{ number_format(abs($payment->amount), 0, ',', '.') }}
                                @if($payment->amount < 0)
                                    <span class="text-xs ml-1">(Negative)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($payment->status == 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @elseif($payment->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($payment->status == 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Failed
                                    </span>
                                @elseif($payment->status == 'refunded')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Refunded
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-slate-600 hover:text-slate-900">
                                        View
                                    </a>
                                    
                                    @if($payment->type != 'refund' && $payment->status == 'pending')
                                        <button wire:click="updateStatus({{ $payment->id }}, 'completed')" class="text-green-600 hover:text-green-900">
                                            Complete
                                        </button>
                                        <button wire:click="updateStatus({{ $payment->id }}, 'failed')" class="text-red-600 hover:text-red-900">
                                            Mark Failed
                                        </button>
                                    @endif
                                    
                                    @if($payment->type != 'refund' && $payment->status == 'completed' && $payment->amount > 0)
                                        <button wire:click="confirmRefund({{ $payment->id }})" class="text-orange-600 hover:text-orange-900">
                                            Refund
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Success Alert
            @this.on('swal:success', (event) => {

                const data = event[0];

                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    confirmButtonColor: '#3085d6'
                });
            });

            // Error Alert
            @this.on('swal:error', (event) => {

                const data = event[0];

                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    </script>
</div>