<div>
    <form wire:submit="completeMaintenance" class="p-6 bg-white rounded-lg shadow">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Complete Maintenance</h2>
            <p class="mt-1 text-gray-600">Complete the maintenance for: <strong>{{ $maintenance->title }}</strong></p>
        </div>

        <div class="mb-6">
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h3 class="font-medium text-gray-900 mb-2">Maintenance Information</h3>
                <p><span class="font-medium">PC:</span> {{ $maintenance->pc->name }} ({{ $maintenance->pc->code }})</p>
                <p><span class="font-medium">Type:</span> {{ ucfirst($maintenance->type) }}</p>
                <p><span class="font-medium">Scheduled:</span> {{ $maintenance->scheduled_date->format('M d, Y H:i') }}</p>
                <p><span class="font-medium">Description:</span> {{ Str::limit($maintenance->description, 150) }}</p>
            </div>
        
            <div class="space-y-4">
                <div>
                    <label for="completed_date" class="block text-sm font-medium text-gray-700">Completed Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" wire:model="completed_date" id="completed_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('completed_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700">Maintenance Cost (Rp)</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" wire:model="cost" id="cost" min="0" step="0.01"
                            class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    @error('cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Completion Notes</label>
                    <textarea wire:model="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.maintenance.show', $maintenance) }}" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 transition">
                Cancel
            </a>
            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">
                Complete Maintenance
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