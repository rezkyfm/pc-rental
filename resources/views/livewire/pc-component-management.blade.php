<div>
    <div class="p-6 bg-white rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Manage PC Components</h3>
        
        @error('selectedComponents')
            <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @enderror
        
        <form wire:submit="updateComponents">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 mb-4">
                    Currently installed: <span class="font-medium">{{ count($selectedComponents) }}</span> components
                </p>
                
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
                                            
                                            @if($component->pcs->contains($pc->id))
                                                @php
                                                    $pivotData = $component->pcs->find($pc->id)->pivot;
                                                @endphp
                                                <p class="text-xs text-blue-600 mt-2">
                                                    Installed on {{ $pivotData->installation_date->format('M d, Y') }}
                                                </p>
                                            @endif
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
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                    Update Components
                </button>
            </div>
        </form>
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