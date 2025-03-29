@extends('layouts.app')

@section('title', 'Browse PCs')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Browse Our Gaming PCs
            </h1>
            <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                Choose from our selection of high-performance gaming PCs available for rent.
            </p>
        </div>

        <!-- Filters -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <form action="{{ route('pcs.browse') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                    <select id="sort" name="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                    </select>
                </div>
                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price (per hour)</label>
                    <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price (per hour)</label>
                    <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- PC Grid -->
        <div class="mt-8 grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @foreach($pcs as $pc)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="bg-gray-200 h-48 flex items-center justify-center">
                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900">{{ $pc->name }}</h3>
                    <p class="mt-2 text-gray-600 line-clamp-2">{{ $pc->description }}</p>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Assembled on {{ $pc->assembly_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between items-center">
                        <div>
                            <span class="text-lg font-bold text-blue-600">Rp {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}</span>
                            <span class="text-gray-500 text-sm">/hour</span>
                        </div>
                        <a href="{{ route('pc.details', $pc) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $pcs->links() }}
        </div>
    </div>
</div>
@endsection