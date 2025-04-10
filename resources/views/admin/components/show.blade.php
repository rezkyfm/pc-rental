@extends('layouts.admin')

@section('title', 'Component Details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.components.index') }}" class="text-slate-600 hover:text-slate-800 transition">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Components
            </div>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-800">{{ $component->name }}</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Component Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Category</p>
                            <p class="mt-1">{{ $component->category->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Brand</p>
                            <p class="mt-1">{{ $component->brand }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Model</p>
                            <p class="mt-1">{{ $component->model }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Serial Number</p>
                            <p class="mt-1">{{ $component->serial_number ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Specifications</p>
                            <p class="mt-1">{{ $component->specifications ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase & Status Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Purchase Date</p>
                            <p class="mt-1">{{ $component->purchase_date ? $component->purchase_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Purchase Price</p>
                            <p class="mt-1">{{ $component->purchase_price ? 'Rp ' . number_format($component->purchase_price, 0, ',', '.') : 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1">
                                @if($component->status == 'available')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @elseif($component->status == 'in_use')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        In Use
                                    </span>
                                @elseif($component->status == 'maintenance')
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
                    </div>
                </div>
            </div>
            
            @if($component->pcs->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Used in PCs</h3>
                    
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <ul role="list" class="divide-y divide-gray-200">
                            @foreach($component->pcs as $pc)
                                <li>
                                    <a href="{{ route('admin.pcs.show', $pc) }}" class="block hover:bg-gray-50">
                                        <div class="px-4 py-4 flex items-center sm:px-6">
                                            <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-slate-600 truncate">{{ $pc->name }}</p>
                                                    <p class="mt-1 text-sm text-gray-500">
                                                        Code: {{ $pc->code }}
                                                        <span class="mx-1">&middot;</span>
                                                        Status: {{ ucfirst($pc->status) }}
                                                    </p>
                                                </div>
                                                <div class="mt-4 flex-shrink-0 sm:mt-0">
                                                    <div class="text-sm text-gray-500">
                                                        Installed on {{ $pc->pivot->installation_date->format('M d, Y') }}
                                                    </div>
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
                </div>
            @endif
            
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.components.edit', $component) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Edit Component
                </a>
                
                @if($component->pcs->count() == 0)
                    <form method="POST" action="{{ route('admin.components.destroy', $component) }}" onsubmit="return confirm('Are you sure you want to delete this component?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete Component
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection