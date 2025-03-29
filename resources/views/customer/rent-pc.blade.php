@extends('layouts.app')

@section('title', 'Rent ' . $pc->name)

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-gray-900">
                    Rent {{ $pc->name }}
                </h1>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    Complete your rental information below
                </p>
            </div>

            <div class="mt-10 md:flex">
                <!-- PC Information -->
                <div class="md:w-1/3 mb-8 md:mb-0 md:pr-8">
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
                            <p class="mt-2 text-gray-600">{{ $pc->description }}</p>
                            <div class="mt-4 flex items-baseline">
                                <span class="text-xl font-bold text-blue-600">Rp
                                    {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}</span>
                                <span class="ml-2 text-gray-500">/hour</span>
                            </div>
                            @if($pc->rental_price_daily)
                                <div class="mt-1 flex items-baseline">
                                    <span class="text-lg font-bold text-blue-600">Rp
                                        {{ number_format($pc->rental_price_daily, 0, ',', '.') }}</span>
                                    <span class="ml-2 text-gray-500">/day</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900">Rental Policies</h3>
                        <ul class="mt-4 space-y-3 text-sm text-gray-600">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>A security deposit is required for all rentals ({{ $depositPercentage }}% of total
                                    rental cost).</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Late returns are subject to additional hourly charges.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>You are responsible for any damage to the equipment during the rental period.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Installation of software is allowed but must be removed before return.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Rental Form -->
                <div class="md:w-2/3">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900">Rental Information</h2>

                        <form action="{{ route('customer.process-rental', $pc) }}" method="POST" class="mt-6"
                            x-data="rentalForm()">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start
                                        Date</label>
                                    <input type="date" id="start_date" name="start_date" min="{{ date('Y-m-d') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start
                                        Time</label>
                                    <select id="start_time" name="start_time" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @for ($i = 8; $i <= 21; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}:00">{{ sprintf('%02d', $i) }}:00</option>
                                        @endfor
                                    </select>
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-700">Rental Duration
                                        (hours)</label>
                                    <input type="number" id="duration" name="duration" min="1" max="72" value="2" required
                                        x-on:input="calculateTotal()"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('duration')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment
                                        Method</label>
                                    <select id="payment_method" name="payment_method" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="cash">Cash</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="transfer">Bank Transfer</option>
                                        <option value="e-wallet">E-Wallet</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cost Summary -->
                            <div class="mt-8 bg-gray-50 p-4 rounded-md">
                                <h3 class="text-lg font-medium text-gray-900">Cost Summary</h3>

                                <div class="mt-4 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Hourly Rate:</span>
                                        <span class="font-medium">Rp
                                            {{ number_format($pc->rental_price_hourly, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duration:</span>
                                        <span x-text="duration + ' hours'" class="font-medium">2 hours</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span x-text="'Rp ' + formatNumber(subtotal)" class="font-medium">Rp
                                            {{ number_format($pc->rental_price_hourly * 2, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                                        <span class="text-gray-700 font-medium">Deposit Required
                                            ({{ $depositPercentage }}%):</span>
                                        <span x-text="'Rp ' + formatNumber(deposit)" class="font-bold">Rp
                                            {{ number_format($depositAmount, 0, ',', '.') }}</span>
                                    </div>
                                    <input type="hidden" name="deposit_amount" x-bind:value="deposit">
                                </div>
                            </div>

                            <div class="mt-8">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" required
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <span class="ml-2 text-gray-700">I agree to the rental terms and conditions</span>
                                </label>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function rentalForm() {
                return {
                    duration: 2,
                    hourlyRate: {{ $pc->rental_price_hourly }},
                    depositPercentage: {{ $depositPercentage }},
                    subtotal: {{ $pc->rental_price_hourly * 2 }},
                    deposit: {{ $depositAmount }},

                    calculateTotal() {
                        this.duration = parseInt(this.duration) || 1;
                        this.subtotal = this.hourlyRate * this.duration;
                        this.deposit = this.subtotal * (this.depositPercentage / 100);
                    },

                    formatNumber(number) {
                        return new Intl.NumberFormat('id-ID').format(number);
                    }
                }
            }
        </script>
    @endpush
@endsection