<div>
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900">Rental Statistics</h3>
            
            <div class="mt-4 md:mt-0">
                <select wire:model.live="period" class="block w-full md:w-auto px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-md">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="last7days">Last 7 Days</option>
                    <option value="last30days">Last 30 Days</option>
                    <option value="thisMonth">This Month</option>
                    <option value="lastMonth">Last Month</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
        </div>
        
        @if($period === 'custom')
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customStart" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" wire:model.live="customStart" id="customStart" 
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-md">
                </div>
                <div>
                    <label for="customEnd" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" wire:model.live="customEnd" id="customEnd" 
                        class="block w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-slate-500 focus:border-slate-500 sm:text-sm rounded-md">
                </div>
            </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Rentals</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalRentals }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <p class="text-sm font-medium text-gray-500 mb-1">Active Rentals</p>
                <p class="text-2xl font-bold text-green-600">{{ $activeRentals }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <p class="text-sm font-medium text-gray-500 mb-1">Completed Rentals</p>
                <p class="text-2xl font-bold text-blue-600">{{ $completedRentals }}</p>
            </div>
            
            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-slate-700">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div>
            <h4 class="text-base font-medium text-gray-900 mb-3">Top Performing PCs</h4>
            
            @if($topPCs->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PC</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Rentals</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($topPCs as $pc)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pc->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pc->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">{{ $pc->rental_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                        Rp {{ number_format($pc->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <p class="text-gray-500">No rental data available for the selected period.</p>
                </div>
            @endif
        </div>
    </div>
</div>