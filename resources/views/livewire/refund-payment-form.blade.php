<div class="p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Process Refund</h3>
    
    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Original Payment</p>
                <p class="font-bold text-gray-900">{{ $originalPayment->payment_number }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Payment Date</p>
                <p>{{ $originalPayment->payment_date->format('M d, Y H:i') }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Payment Type</p>
                <p>{{ ucfirst($originalPayment->type) }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Original Amount</p>
                <p class="font-medium text-green-600">Rp {{ number_format($originalPayment->amount, 0, ',', '.') }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Payment Method</p>
                <p>{{ ucwords(str_replace('_', ' ', $originalPayment->method)) }}</p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-gray-500">Maximum Refund Amount</p>
                <p class="font-medium text-red-600">Rp {{ number_format($maxRefundAmount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <form wire:submit="createRefund">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Refund Amount <span class="text-red-500">*</span></label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" wire:model="amount" id="amount" min="0.01" max="{{ $maxRefundAmount }}" step="0.01" required
                        class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                    Maximum refund amount: Rp {{ number_format($maxRefundAmount, 0, ',', '.') }}
                </p>
            </div>
            
            <div>
                <label for="method" class="block text-sm font-medium text-gray-700">Refund Method <span class="text-red-500">*</span></label>
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
                <label for="payment_date" class="block text-sm font-medium text-gray-700">Refund Date <span class="text-red-500">*</span></label>
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
        
        <div class="flex justify-end space-x-3">
            <button type="button" wire:click="$dispatch('closeModal')" 
                class="bg-white text-gray-700 py-2 px-4 border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </button>
            <button type="submit" 
                class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                Process Refund
            </button>
        </div>
    </form>
</div>