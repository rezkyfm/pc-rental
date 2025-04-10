<div>
    <form wire:submit="createRental" class="p-6 bg-white rounded-lg shadow">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer & PC Selection</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Customer <span class="text-red-500">*</span></label>
                        <select wire:model="user_id" id="user_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="pc_id" class="block text-sm font-medium text-gray-700">PC <span class="text-red-500">*</span></label>
                        <select wire:model="pc_id" id="pc_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select PC</option>
                            @foreach($availablePCs as $pc)
                                <option value="{{ $pc->id }}">{{ $pc->name }} ({{ $pc->code }}) - Rp {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}/hour</option>
                            @endforeach
                        </select>
                        @error('pc_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    @if($selectedPC)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2">Selected PC Information</h4>
                            <p><span class="font-medium">Name:</span> {{ $selectedPC->name }}</p>
                            <p><span class="font-medium">Code:</span> {{ $selectedPC->code }}</p>
                            <p><span class="font-medium">Hourly Rate:</span> Rp {{ number_format($selectedPC->rental_price_hourly, 0, ',', '.') }}</p>
                            @if($selectedPC->description)
                                <p class="text-sm text-gray-500 mt-2">{{ Str::limit($selectedPC->description, 100) }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" wire:model="start_time" id="start_time" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" wire:model="end_time" id="end_time" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea wire:model="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Duration:</span>
                            <span class="font-medium">{{ $rentalHours }} hours</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Hourly Rate:</span>
                            <span class="font-medium">Rp {{ $selectedPC ? number_format($selectedPC->rental_price_hourly, 0, ',', '.') : '0' }}</span>
                        </div>
                        <div class="flex justify-between mb-2 border-t border-gray-200 pt-2">
                            <span class="text-gray-600">Estimated Total:</span>
                            <span class="font-medium">Rp {{ number_format($estimatedTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-medium text-blue-600">
                            <span>Required Deposit ({{ $depositPercentage }}%):</span>
                            <span>Rp {{ number_format($deposit_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div>
                        <label for="deposit_amount" class="block text-sm font-medium text-gray-700">Deposit Amount <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model="deposit_amount" id="deposit_amount" min="0" step="0.01" required
                                class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('deposit_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
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
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                Create Rental
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