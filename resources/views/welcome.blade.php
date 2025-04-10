@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="py-12">
        <!-- Hero section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-800 rounded-lg shadow-xl overflow-hidden">
                <div class="px-6 py-12 md:py-20 md:px-12 text-center md:text-left space-y-6 md:space-y-8">
                    <h1 class="text-3xl md:text-5xl font-bold text-white leading-tight">
                        Premium Gaming PCs for Rent
                    </h1>
                    <p class="text-lg md:text-xl text-blue-100 max-w-3xl">
                        Experience the latest gaming hardware without the commitment. Rent high-performance PCs for gaming,
                        streaming, or content creation.
                    </p>
                    <div
                        class="space-y-3 md:space-y-0 md:space-x-4 flex flex-col md:flex-row md:items-center md:justify-start">
                        <a href="{{ route('pcs.browse') }}"
                            class="inline-block bg-white text-slate-800 font-bold px-6 py-3 rounded-md hover:bg-slate-50 transition">
                            Browse PCs
                        </a>
                        <a href="#how-it-works"
                            class="inline-block text-white border border-white font-semibold px-6 py-3 rounded-md hover:bg-slate-700 transition">
                            How It Works
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- How it works section -->
        <div id="how-it-works" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">
                    How It Works
                </h2>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    Renting a gaming PC has never been easier. Follow these simple steps to get started.
                </p>
            </div>

            <div class="mt-10 grid gap-8 grid-cols-1 md:grid-cols-3">
                <div class="text-center">
                    <div
                        class="mx-auto h-16 w-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-800 text-2xl font-bold">
                        1</div>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">Choose a PC</h3>
                    <p class="mt-2 text-gray-600">Browse our selection of high-performance gaming PCs and choose the one
                        that meets your needs.</p>
                </div>
                <div class="text-center">
                    <div
                        class="mx-auto h-16 w-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-800 text-2xl font-bold">
                        2</div>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">Book Your Rental</h3>
                    <p class="mt-2 text-gray-600">Choose your rental duration and make a reservation with a small deposit
                        payment.</p>
                </div>
                <div class="text-center">
                    <div
                        class="mx-auto h-16 w-16 rounded-full bg-slate-200 flex items-center justify-center text-slate-800 text-2xl font-bold">
                        3</div>
                    <h3 class="mt-4 text-xl font-medium text-gray-900">Game On!</h3>
                    <p class="mt-2 text-gray-600">Visit our store to pick up your rental PC or opt for our delivery service.
                        Then just enjoy gaming!</p>
                </div>
            </div>
        </div>

        <!-- Featured PCs section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-28">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">
                    Featured PCs
                </h2>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    Check out our most popular gaming rigs, ready for your next gaming session or streaming event.
                </p>
            </div>

            <div class="mt-10 grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach($pcs as $pc)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-gray-200 h-48 flex items-center justify-center">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900">{{ $pc->name }}</h3>
                            <p class="mt-2 text-gray-600 line-clamp-2">{{ $pc->description }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <div>
                                    <span class="text-lg font-bold text-slate-600">Rp
                                        {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}</span>
                                    <span class="text-gray-500 text-sm">/hour</span>
                                </div>
                                <a href="{{ route('pc.details', $pc) }}"
                                    class="inline-block bg-slate-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-slate-700 transition">View
                                    Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('pcs.browse') }}"
                    class="inline-block bg-slate-600 text-white font-medium px-6 py-3 rounded-md hover:bg-slate-700 transition">
                    View All PCs
                </a>
            </div>
        </div>

    </div>
@endsection