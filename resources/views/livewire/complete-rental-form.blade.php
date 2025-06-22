<div class="p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Complete Rental</h3>
    
    @if($errors->has('status'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ $errors->first('status') }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <p class="font-medium">{{ $rental->invoice_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Customer</p>
                    <p class="font-medium">{{ $rental->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">PC</p>
                    <p class="font-medium">{{ $rental->pc->name }} ({{ $rental->pc->code }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Hourly Rate</p>
                    <p class="font-medium">Rp {{ number_format($rental->pc->rental_price_hourly, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Start Time</p>
                    <p class="font-medium">{{ $rental->start_time->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Scheduled End Time</p>
                    <p class="font-medium">{{ $rental->end_time->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <form wire:submit="completeRental">
            <div class="space-y-4">
                <div>
                    <label for="actual_return_time" class="block text-sm font-medium text-gray-700">Actual Return Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" wire:model="actual_return_time" id="actual_return_time" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('actual_return_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Total Duration:</span>
                        <span class="font-medium">{{ $totalHours }} hours</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Hourly Rate:</span>
                        <span class="font-medium">Rp {{ number_format($rental->pc->rental_price_hourly, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-medium">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Deposit Paid:</span>
                        <span class="font-medium">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-medium text-blue-600 border-t border-gray-200 pt-2">
                        <span>Remaining Payment:</span>
                        <span>Rp {{ number_format($remainingPayment, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                @if($remainingPayment > 0)
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method <span class="text-red-500">*</span></label>
                        <select wire:model="payment_method" id="payment_method" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="transfer">Bank Transfer</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        wire:click="$dispatch('closeModal')">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Complete Rental
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>