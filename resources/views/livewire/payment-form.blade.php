<div>
    <form wire:submit="createPayment" class="p-6 bg-white rounded-lg shadow">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Information</h3>
            
            <div>
                <label for="rental_id" class="block text-sm font-medium text-gray-700">Select Rental <span class="text-red-500">*</span></label>
                <select wire:model.live="rental_id" id="rental_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a Rental</option>
                    @foreach($rentals as $rental)
                        <option value="{{ $rental->id }}">
                            {{ $rental->invoice_number }} - {{ $rental->user->name }} - {{ $rental->pc->name }} 
                            ({{ $rental->status }})
                        </option>
                    @endforeach
                </select>
                @error('rental_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            @if($selectedRental)
                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Invoice Number</p>
                            <p class="mt-1">{{ $selectedRental->invoice_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Customer</p>
                            <p class="mt-1">{{ $selectedRental->user->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">PC</p>
                            <p class="mt-1">{{ $selectedRental->pc->name }} ({{ $selectedRental->pc->code }})</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1">
                                @if($selectedRental->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($selectedRental->status == 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($selectedRental->status == 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Completed
                                    </span>
                                @elseif($selectedRental->status == 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Cancelled
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Rental Period</p>
                            <p class="mt-1">
                                {{ $selectedRental->start_time->format('M d, Y H:i') }} - 
                                {{ $selectedRental->end_time->format('M d, Y H:i') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Price</p>
                            <p class="mt-1">Rp {{ number_format($totalRentalAmount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Deposit Paid</p>
                                <p class="mt-1 text-blue-600 font-medium">
                                    Rp {{ number_format($depositPaid, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Remaining Balance</p>
                                <p class="mt-1 {{ $remainingBalance > 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                    Rp {{ number_format(abs($remainingBalance), 0, ',', '.') }}
                                    @if($remainingBalance < 0)
                                        (Overpaid)
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <p class="text-sm font-medium text-gray-500">Suggested Payment</p>
                                <p class="mt-1 text-gray-900 font-bold">
                                    Rp {{ number_format($suggestedAmount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Payment Type <span class="text-red-500">*</span></label>
                    <select wire:model.live="type" id="type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="deposit">Deposit</option>
                        <option value="rental">Rental</option>
                        <option value="extra">Extra Charges</option>
                        <option value="refund">Refund</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="method" class="block text-sm font-medium text-gray-700">Payment Method <span class="text-red-500">*</span></label>
                    <select wire:model="method" id="method" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="transfer">Bank Transfer</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                    @error('method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">
                        Amount <span class="text-red-500">*</span>
                        @if($type === 'refund')
                            <span class="text-xs text-red-500">(Will be recorded as negative)</span>
                        @endif
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" wire:model="amount" id="amount" min="0.01" step="0.01" required
                            class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" wire:model="payment_date" id="payment_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea wire:model="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                Record Payment
            </button>
        </div>
    </form>
    
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