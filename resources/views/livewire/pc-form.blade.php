<div>
    <form wire:submit="save" class="p-6 bg-white rounded-lg shadow">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">PC Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Code <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="code" id="code" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea wire:model="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rental & Status Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="rental_price_hourly" class="block text-sm font-medium text-gray-700">
                            Hourly Rental Price <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model="rental_price_hourly" id="rental_price_hourly" step="0.01" min="0" required
                                class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('rental_price_hourly')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="rental_price_daily" class="block text-sm font-medium text-gray-700">
                            Daily Rental Price
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model="rental_price_daily" id="rental_price_daily" step="0.01" min="0"
                                class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('rental_price_daily')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="assembly_date" class="block text-sm font-medium text-gray-700">
                            Assembly Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="assembly_date" id="assembly_date" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('assembly_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select wire:model="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="available">Available</option>
                            <option value="rented">Rented</option>
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
        
        <!-- Components Section -->
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Components</h3>
            
            @error('selectedComponents')
                <p class="mt-1 mb-4 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            <div class="bg-gray-50 rounded-lg p-4">
                @forelse($categories as $category)
                    @if($category->components->count() > 0)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-2">{{ $category->name }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($category->components as $component)
                                    <label class="relative flex items-start p-4 rounded-lg border {{ in_array($component->id, $selectedComponents) ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }} hover:border-blue-400 transition cursor-pointer">
                                        <div class="min-w-0 flex-1 text-sm">
                                            <input type="checkbox" wire:model="selectedComponents" value="{{ $component->id }}" class="absolute h-5 w-5 top-4 right-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <p class="font-medium text-gray-700">{{ $component->name }}</p>
                                            <p class="text-gray-500 truncate">{{ $component->brand }} {{ $component->model }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($component->specifications, 50) }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-sm text-gray-500">No components available.</p>
                @endforelse
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                {{ $isEdit ? 'Update' : 'Create' }} PC
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