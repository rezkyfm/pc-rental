<div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-medium text-gray-900 mb-4">PC Availability</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <p class="text-sm font-medium text-green-800">Available</p>
                <p class="text-2xl font-bold text-green-700">{{ $availablePCs->count() }}</p>
                <p class="text-xs text-green-600 mt-1">Ready for rental</p>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <p class="text-sm font-medium text-blue-800">Rented</p>
                <p class="text-2xl font-bold text-blue-700">{{ $rentedPCs->count() }}</p>
                <p class="text-xs text-blue-600 mt-1">Currently in use</p>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <p class="text-sm font-medium text-yellow-800">Maintenance</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $maintenancePCs->count() }}</p>
                <p class="text-xs text-yellow-600 mt-1">Under repair</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <p class="text-sm font-medium text-gray-800">Retired</p>
                <p class="text-2xl font-bold text-gray-700">{{ $retiredPCs->count() }}</p>
                <p class="text-xs text-gray-600 mt-1">No longer in service</p>
            </div>
        </div>
        
        <!-- Available PCs -->
        <div x-data="{ open: true }" class="mb-4">
            <div @click="open = !open" class="flex justify-between items-center cursor-pointer p-2 bg-gray-50 rounded-t-lg border border-gray-200">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="inline-block h-2 w-2 bg-green-500 rounded-full mr-2"></span>
                    Available PCs ({{ $availablePCs->count() }})
                </h4>
                <svg x-show="!open" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                <svg x-show="open" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </div>
            
            <div x-show="open" class="border-l border-r border-b border-gray-200 rounded-b-lg overflow-hidden">
                @if($availablePCs->isEmpty())
                    <div class="p-4 text-center text-sm text-gray-500">
                        No available PCs at the moment.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-px bg-gray-200">
                        @foreach($availablePCs as $pc)
                            <div class="p-4 bg-white hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $pc->name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $pc->code }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Available
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">
                                    Rp {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}/hour
                                </p>
                                <div class="mt-3 flex justify-end space-x-2">
                                    <a href="{{ route('admin.rentals.create', ['pc_id' => $pc->id]) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        Create Rental
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('admin.pcs.show', $pc) }}" class="text-slate-600 hover:text-slate-900 text-sm font-medium">
                                        View PC
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Currently Rented PCs -->
        <div x-data="{ open: true }" class="mb-4">
            <div @click="open = !open" class="flex justify-between items-center cursor-pointer p-2 bg-gray-50 rounded-t-lg border border-gray-200">
                <h4 class="text-base font-medium text-gray-900 flex items-center">
                    <span class="inline-block h-2 w-2 bg-blue-500 rounded-full mr-2"></span>
                    Currently Rented PCs ({{ $rentedPCs->count() }})
                </h4>
                <svg x-show="!open" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                <svg x-show="open" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </div>
            
            <div x-show="open" class="border-l border-r border-b border-gray-200 rounded-b-lg overflow-hidden">
                @if($rentedPCs->isEmpty())
                    <div class="p-4 text-center text-sm text-gray-500">
                        No PCs are currently rented.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PC</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental Period</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($rentedPCs as $pc)
                                    @if($pc->current_rental)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $pc->name }}
                                                <div class="text-xs text-gray-500">{{ $pc->code }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $pc->current_rental->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($pc->current_rental->start_time)->format('M d, g:i A') }} -
                                                {{ \Carbon\Carbon::parse($pc->current_rental->end_time)->format('M d, g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if(\Carbon\Carbon::now()->gt($pc->current_rental->end_time))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Overdue
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Active
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.rentals.show', $pc->current_rental) }}" class="text-blue-600 hover:text-blue-900">View Rental</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="flex justify-end mt-4">
            <a href="{{ route('admin.pcs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View All PCs →
            </a>
        </div>
    </div>
</div>