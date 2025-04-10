<div>
    <form wire:submit="save" class="p-6 bg-white rounded-lg shadow">
        <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                
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
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" id="email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="tel" wire:model="phone" id="phone"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Authentication & Role</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password {{ $isEdit ? '' : '<span class="text-red-500">*</span>' }}
                        </label>
                        <input type="password" wire:model="password" id="password" {{ $isEdit ? '' : 'required' }}
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @if($isEdit)
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password.</p>
                        @endif
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm Password {{ $isEdit ? '' : '<span class="text-red-500">*</span>' }}
                        </label>
                        <input type="password" wire:model="password_confirmation" id="password_confirmation" {{ $isEdit ? '' : 'required' }}
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                        <select wire:model="role" id="role" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="admin">Admin</option>
                            <option value="operator">Operator</option>
                            <option value="customer">Customer</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                {{ $isEdit ? 'Update' : 'Create' }} User
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