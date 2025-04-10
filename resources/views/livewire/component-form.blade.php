<div>
    <form wire:submit="save" class="p-6 bg-white rounded-lg shadow">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Component Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select wire:model="category_id" id="category_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700">Brand <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="brand" id="brand" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('brand')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700">Model <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="model" id="model" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="specifications" class="block text-sm font-medium text-gray-700">Specifications</label>
                        <textarea wire:model="specifications" id="specifications" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('specifications')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase & Status Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="purchase_date" class="block text-sm font-medium text-gray-700">Purchase Date</label>
                        <input type="date" wire:model="purchase_date" id="purchase_date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('purchase_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700">Purchase Price</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model="purchase_price" id="purchase_price" step="0.01" min="0"
                                class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('purchase_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                        <input type="text" wire:model="serial_number" id="serial_number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('serial_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select wire:model="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="available">Available</option>
                            <option value="in_use">In Use</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="retired">Retired</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                {{ $isEdit ? 'Update' : 'Create' }} Component
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