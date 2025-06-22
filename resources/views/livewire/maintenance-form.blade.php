<div>
    <form wire:submit="{{ $isEdit ? 'updateMaintenance' : 'createMaintenance' }}" class="p-6 bg-white rounded-lg shadow">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">PC & Maintenance Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="pc_id" class="block text-sm font-medium text-gray-700">PC <span class="text-red-500">*</span></label>
                        <select wire:model="pc_id" id="pc_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            {{ $isEdit && $status === 'in_progress' ? 'disabled' : '' }}>
                            <option value="">Select PC</option>
                            @foreach($pcs as $pc)
                                <option value="{{ $pc->id }}">{{ $pc->name }} ({{ $pc->code }})</option>
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
                            <p><span class="font-medium">Status:</span> 
                                @if($selectedPC->status == 'available')
                                    <span class="text-green-600">Available</span>
                                @elseif($selectedPC->status == 'rented')
                                    <span class="text-blue-600">Rented</span>
                                @elseif($selectedPC->status == 'maintenance')
                                    <span class="text-yellow-600">In Maintenance</span>
                                @else
                                    <span class="text-gray-600">Retired</span>
                                @endif
                            </p>
                        </div>
                    @endif
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" id="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Type <span class="text-red-500">*</span></label>
                        <select wire:model="type" id="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="routine">Routine Maintenance</option>
                            <option value="repair">Repair</option>
                            <option value="upgrade">Upgrade</option>
                            <option value="other">Other</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule & Status</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Scheduled Date <span class="text-red-500">*</span></label>
                        <input type="datetime-local" wire:model="scheduled_date" id="scheduled_date" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('scheduled_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select wire:model="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="scheduled">Scheduled</option>
                            <option value="in_progress">In Progress</option>
                            @if($isEdit)
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            @endif
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="performed_by" class="block text-sm font-medium text-gray-700">Performed By <span class="text-red-500">*</span></label>
                        <select wire:model="performed_by" id="performed_by" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select Staff</option>
                            @foreach($staff as $person)
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                            @endforeach
                        </select>
                        @error('performed_by')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    @if($isEdit && ($status === 'completed' || $status === 'in_progress'))
                        <div>
                            <label for="completed_date" class="block text-sm font-medium text-gray-700">Completion Date 
                                @if($status === 'completed')<span class="text-red-500">*</span>@endif
                            </label>
                            <input type="datetime-local" wire:model="completed_date" id="completed_date" 
                                {{ $status === 'completed' ? 'required' : '' }}
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('completed_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="cost" class="block text-sm font-medium text-gray-700">Cost (Rp)</label>
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
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
            <textarea wire:model="description" id="description" rows="5" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.maintenance.index') }}" class="bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 transition">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                {{ $isEdit ? 'Update' : 'Create' }} Maintenance
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