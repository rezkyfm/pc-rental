@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">PCs</h3>
                    <div class="flex space-x-4 mt-1">
                        <div>
                            <span class="text-2xl font-bold text-gray-900">{{ $stats['total_pcs'] }}</span>
                            <span class="text-sm text-gray-500">Total</span>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-green-600">{{ $stats['available_pcs'] }}</span>
                            <span class="text-sm text-gray-500">Available</span>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-blue-600">{{ $stats['rented_pcs'] }}</span>
                            <span class="text-sm text-gray-500">Rented</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Rentals</h3>
                    <div class="flex space-x-4 mt-1">
                        <div>
                            <span class="text-2xl font-bold text-blue-600">{{ $stats['active_rentals'] }}</span>
                            <span class="text-sm text-gray-500">Active</span>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-yellow-600">{{ $stats['pending_rentals'] }}</span>
                            <span class="text-sm text-gray-500">Pending</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Components</h3>
                    <div class="flex space-x-4 mt-1">
                        <div>
                            <span class="text-2xl font-bold text-gray-900">{{ $stats['total_components'] }}</span>
                            <span class="text-sm text-gray-500">Total</span>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-green-600">{{ $stats['available_components'] }}</span>
                            <span class="text-sm text-gray-500">Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Maintenance</h3>
                    <div class="flex space-x-4 mt-1">
                        <div>
                            <span class="text-2xl font-bold text-gray-900">{{ $stats['upcoming_maintenance'] }}</span>
                            <span class="text-sm text-gray-500">Upcoming</span>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-blue-600">{{ $stats['maintenance_pcs'] }}</span>
                            <span class="text-sm text-gray-500">PCs in maintenance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Rentals -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Recent Rentals</h2>
                <a href="{{ route('admin.rentals.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="p-6">
                @if($recentRentals->isEmpty())
                    <p class="text-gray-500">No recent rentals found.</p>
                @else
                    <div class="space-y-4">
                        @foreach($recentRentals as $rental)
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $rental->pc->name }}</div>
                                            <div class="text-xs text-gray-500">Rented by {{ $rental->user->name }}</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $rental->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $rental->status === 'pending' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $rental->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                                    {{ $rental->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($rental->status) }}
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $rental->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Maintenance -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Upcoming Maintenance</h2>
                <a href="{{ route('admin.maintenance.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View
                    All</a>
            </div>
            <div class="p-6">
                @if($upcomingMaintenance->isEmpty())
                    <p class="text-gray-500">No upcoming maintenance tasks.</p>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingMaintenance as $maintenance)
                            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $maintenance->title }}</div>
                                            <div class="text-xs text-gray-500">PC: {{ $maintenance->pc->name }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">
                                                <span
                                                    class="text-blue-600 font-medium">{{ $maintenance->scheduled_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $maintenance->scheduled_date->format('H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- PC Utilization -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-medium text-gray-900">PC Utilization</h2>
            </div>
            <div class="p-6">
                @if($pcUtilization->isEmpty())
                    <p class="text-gray-500">No PC utilization data available.</p>
                @else
                        <div class="space-y-6">
                            @foreach($pcUtilization as $pc)
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $pc->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $pc->rentals_count }} rentals</div>
                                            </div>
                                            <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                                                @php
                                                    $percentage = min(100, ($pc->rentals_count / max(1, $pcUtilization->max('rentals_count'))) * 100);
                                                @endphp
                                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                            @endforeach
                        </div>
                @endif
            </div>
        </div>
    </div>
@endsection