<div>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900">
                PC Rentals Calendar - {{ Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y') }}
            </h3>
            
            <div class="mt-4 md:mt-0 flex space-x-2">
                <button type="button" wire:click="previousMonth" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Previous
                </button>
                
                <button type="button" wire:click="goToCurrentMonth" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Today
                </button>
                
                <button type="button" wire:click="nextMonth" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Next
                    <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Calendar Grid -->
        <div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200 rounded overflow-hidden">
            <!-- Day Headers -->
            <div class="bg-gray-100 text-center py-2 font-medium text-gray-500">Sun</div>
            <div class="bg-gray-100 text-center py-2 font-medium text-gray-500">Mon</div>
            <div class="bg-gray-100 text-center py-2 font-medium text-gray-500">Tue</div>
            <div class="bg-gray-100 text-center py-2 font-medium text-gray-500">Wed</div>
            <div class="bg-gray-100 text-center py-2 font-medium text-gray-500">Thu</div>
            <div class="bg-gray-100 text-center py-2 font-medium text-gray-500">Fri</div>
            <div class="bg-gray-100 text-center py-2 font-medium text-gray-500">Sat</div>
            
            <!-- Calendar Days -->
            @foreach($calendarData as $day)
                <div 
                    wire:click="viewDayDetails('{{ $day['formattedDate'] }}')"
                    class="bg-white min-h-[100px] p-2 {{ !$day['isCurrentMonth'] ? 'bg-gray-50' : '' }} {{ $day['isToday'] ? 'border-2 border-blue-500' : '' }} relative cursor-pointer hover:bg-gray-50"
                >
                    <div class="font-medium text-sm {{ !$day['isCurrentMonth'] ? 'text-gray-400' : 'text-gray-700' }} {{ $day['isToday'] ? 'text-blue-500' : '' }}">
                        {{ $day['day'] }}
                    </div>
                    
                    @if($day['isCurrentMonth'])
                        <div class="mt-1">
                            @if(isset($day['rentalCount']) && $day['rentalCount'] > 0)
                                <div class="text-xs mb-1 text-blue-600 font-medium">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $day['rentalCount'] }} Rentals
                                    </span>
                                </div>
                            @endif
                            
                            @if(isset($day['availablePCs']))
                                <div class="text-xs text-green-600 font-medium">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium {{ $day['availablePCs'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $day['availablePCs'] }} Available
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="mt-4 text-sm text-gray-500">
            <p>Click on any day to view detailed PC rental information.</p>
        </div>
    </div>
    
    <!-- Day Details Modal -->
    @if($showDetails)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" wire:click.self="closeDetails">
            <div class="bg-white rounded-lg max-w-3xl w-full mx-4 max-h-[80vh] overflow-y-auto" @click.outside="$wire.closeDetails()">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">
                        PC Rentals for {{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
                    </h3>
                    <button type="button" wire:click="closeDetails" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <h4 class="text-base font-medium text-gray-900 mb-2">Rented PCs</h4>
                        
                        @if($selectedDateRentals->count() > 0)
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PC</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($selectedDateRentals as $rental)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $rental->pc->name }}
                                                    <div class="text-xs text-gray-500">{{ $rental->pc->code }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $rental->user->name }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ Carbon\Carbon::parse($rental->start_time)->format('g:i A') }} - 
                                                    {{ Carbon\Carbon::parse($rental->end_time)->format('g:i A') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('admin.rentals.show', $rental) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-gray-500">No rentals for this day.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <h4 class="text-base font-medium text-gray-900 mb-2">Available PCs</h4>
                        
                        @if($selectedDateAvailablePCs->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($selectedDateAvailablePCs as $pc)
                                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                                        <h5 class="font-medium text-gray-900">{{ $pc->name }}</h5>
                                        <p class="text-sm text-gray-500">{{ $pc->code }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Rate: Rp {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}/hour
                                        </p>
                                        <div class="mt-2">
                                            <a href="{{ route('admin.rentals.create', ['pc_id' => $pc->id, 'date' => $selectedDate]) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                Create Rental
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <p class="text-gray-500">No PCs available for this day.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>