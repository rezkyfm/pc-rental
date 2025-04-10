@extends('layouts.admin')

@section('title', 'PC Details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.pcs.index') }}" class="text-slate-600 hover:text-slate-800 transition">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to PCs
            </div>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $pc->name }}</h2>
                    <p class="text-sm text-gray-600">Code: {{ $pc->code }}</p>
                </div>
                <div>
                    @if($pc->status == 'available')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Available
                        </span>
                    @elseif($pc->status == 'rented')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Rented
                        </span>
                    @elseif($pc->status == 'maintenance')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Maintenance
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Retired
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">PC Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name</p>
                            <p class="mt-1">{{ $pc->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Code</p>
                            <p class="mt-1">{{ $pc->code }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Description</p>
                            <p class="mt-1">{{ $pc->description ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Assembly Date</p>
                            <p class="mt-1">{{ $pc->assembly_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Hourly Rate</p>
                            <p class="mt-1">Rp {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Daily Rate</p>
                            <p class="mt-1">
                                @if($pc->rental_price_daily)
                                    Rp {{ number_format($pc->rental_price_daily, 0, ',', '.') }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1">
                                @if($pc->status == 'available')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @elseif($pc->status == 'rented')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Rented
                                    </span>
                                @elseif($pc->status == 'maintenance')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Maintenance
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Retired
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        @php
                            $activeRentals = $pc->rentals()->whereIn('status', ['active', 'pending'])->count();
                            $completedRentals = $pc->rentals()->where('status', 'completed')->count();
                            $totalRentals = $pc->rentals()->count();
                        @endphp
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Rental Statistics</p>
                            <div class="mt-1 flex space-x-4">
                                <div class="text-center">
                                    <span class="block text-lg font-bold text-blue-600">{{ $activeRentals }}</span>
                                    <span class="text-xs text-gray-500">Active</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-lg font-bold text-green-600">{{ $completedRentals }}</span>
                                    <span class="text-xs text-gray-500">Completed</span>
                                </div>
                                <div class="text-center">
                                    <span class="block text-lg font-bold text-gray-600">{{ $totalRentals }}</span>
                                    <span class="text-xs text-gray-500">Total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Components Section -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Components ({{ $pc->components->count() }})</h3>
                    <button type="button" x-data="{}" 
                        x-on:click="window.livewire.dispatch('openModal', { component: 'pc-component-management', arguments: { pc: {{ $pc->id }} }})"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Manage Components
                    </button>
                </div>
                
                @if($pc->components->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($pc->components->sortBy('category.name') as $component)
                            <div class="bg-white border rounded-lg overflow-hidden">
                                <div class="px-4 py-3 border-b bg-gray-50">
                                    <h4 class="font-medium text-gray-900">{{ $component->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $component->category->name }}</p>
                                </div>
                                <div class="p-4">
                                    <div class="text-sm">
                                        <p class="text-gray-600">
                                            <span class="font-medium">Brand:</span> {{ $component->brand }}
                                        </p>
                                        <p class="text-gray-600">
                                            <span class="font-medium">Model:</span> {{ $component->model }}
                                        </p>
                                        @if($component->specifications)
                                            <p class="text-gray-600 mt-2 text-xs">
                                                {{ Str::limit($component->specifications, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="mt-3 text-xs text-gray-500">
                                        Installed on {{ $component->pivot->installation_date->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-500">No components added to this PC yet.</p>
                    </div>
                @endif
            </div>
            
            <!-- Rental History Section -->
            @if($pc->rentals->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Rental History</h3>
                    
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach($pc->rentals->take(5) as $rental)
                                <li>
                                    <a href="{{ route('admin.rentals.show', $rental) }}" class="block hover:bg-gray-50">
                                        <div class="px-4 py-4 flex items-center sm:px-6">
                                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-slate-600 truncate">
                                                        Invoice #{{ $rental->invoice_number }}
                                                    </p>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        {{ $rental->user->name }}
                                                        <span class="mx-1">&middot;</span>
                                                        {{ $rental->start_time->format('M d, Y') }} to {{ $rental->end_time->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="mt-4 flex-shrink-0 sm:mt-0">
                                                    @if($rental->status == 'active')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Active
                                                        </span>
                                                    @elseif($rental->status == 'pending')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Pending
                                                        </span>
                                                    @elseif($rental->status == 'completed')
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Completed
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            Cancelled
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="ml-5 flex-shrink-0">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    @if($pc->rentals->count() > 5)
                        <div class="mt-4 text-right">
                            <a href="{{ route('admin.rentals.index', ['pc_id' => $pc->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                View All Rental History →
                            </a>
                        </div>
                    @endif
                </div>
            @endif
            
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.pcs.edit', $pc) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Edit PC
                </a>
                
                @if(!$pc->rentals()->whereIn('status', ['active', 'pending'])->exists())
                    <form method="POST" action="{{ route('admin.pcs.destroy', $pc) }}" onsubmit="return confirm('Are you sure you want to delete this PC?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete PC
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection