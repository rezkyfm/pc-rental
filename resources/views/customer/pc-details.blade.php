@extends('layouts.app')

@section('title', $pc->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2">
                    <div class="bg-gray-200 h-96 flex items-center justify-center">
                        <svg class="w-48 h-48 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="md:w-1/2 p-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $pc->name }}</h1>
                    <p class="mt-2 text-gray-600">{{ $pc->description }}</p>
                    
                    <div class="mt-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="ml-2 text-gray-700">Available for Rental</span>
                        </div>
                        <div class="flex items-center mt-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="ml-2 text-gray-700">Assembled on {{ $pc->assembly_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <div class="flex items-baseline">
                            <span class="text-3xl font-bold text-slate-600">Rp {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}</span>
                            <span class="ml-2 text-gray-500">/hour</span>
                        </div>
                        @if($pc->rental_price_daily)
                        <div class="mt-1 flex items-baseline">
                            <span class="text-xl font-bold text-slate-600">Rp {{ number_format($pc->rental_price_daily, 0, ',', '.') }}</span>
                            <span class="ml-2 text-gray-500">/day</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-8">
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('customer.rent', $pc) }}" class="inline-block w-full bg-slate-600 text-white text-center py-3 px-4 rounded-md font-medium hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition">
                                    Rent This PC
                                </a>
                            @else
                                <div class="text-center p-4 bg-gray-100 rounded-md">
                                    <p class="text-gray-600">You need a customer account to rent PCs.</p>
                                </div>
                            @endif
                        @else
                            <div class="space-y-4">
                                <a href="{{ route('login') }}" class="inline-block w-full bg-slate-600 text-white text-center py-3 px-4 rounded-md font-medium hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition">
                                    Login to Rent
                                </a>
                                <p class="text-center text-gray-600">Don't have an account? 
                                    <a href="{{ route('register') }}" class="text-slate-600 hover:underline">Register here</a>
                                </p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 px-8 py-6">
                <h2 class="text-xl font-bold text-gray-900">PC Specifications</h2>
                
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                    @foreach($pc->components->groupBy('category.name') as $categoryName => $components)
                        <div class="bg-gray-50 p-4 rounded-md">
                            <h3 class="font-medium text-gray-900">{{ $categoryName }}</h3>
                            <ul class="mt-2 space-y-2">
                                @foreach($components as $component)
                                    <li class="text-gray-600">
                                        <span class="font-medium">{{ $component->name }}</span>
                                        <p class="text-sm text-gray-500">{{ $component->specifications }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Similar PCs section -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900">Similar PCs</h2>
            <div class="mt-6 grid gap-8 grid-cols-1 md:grid-cols-3">
                @foreach($similarPCs as $similarPC)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="bg-gray-200 h-48 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900">{{ $similarPC->name }}</h3>
                        <p class="mt-2 text-gray-600 line-clamp-2">{{ $similarPC->description }}</p>
                        <div class="mt-6 flex justify-between items-center">
                            <div>
                                <span class="text-lg font-bold text-slate-600">Rp {{ number_format($similarPC->rental_price_hourly, 0, ',', '.') }}</span>
                                <span class="text-gray-500 text-sm">/hour</span>
                            </div>
                            <a href="{{ route('pc.details', $similarPC) }}" class="inline-block bg-slate-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-slate-700 transition">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection