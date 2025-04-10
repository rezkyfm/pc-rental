@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-slate-600 hover:text-slate-800 transition">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Users
            </div>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center">
            <div class="h-12 w-12 rounded-full bg-slate-600 flex items-center justify-center text-white font-bold text-lg mr-4">
                {{ $user->initials() }}
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name</p>
                            <p class="mt-1">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="mt-1">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Phone</p>
                            <p class="mt-1">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Role</p>
                            <p class="mt-1">
                                @if($user->role == 'admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Admin
                                    </span>
                                @elseif($user->role == 'operator')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Operator
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Customer
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Member Since</p>
                            <p class="mt-1">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Activity</h3>
                    
                    @php
                        $activeRentals = $user->rentals()->whereIn('status', ['active', 'pending'])->count();
                        $completedRentals = $user->rentals()->where('status', 'completed')->count();
                        $totalRentals = $user->rentals()->count();
                    @endphp
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-blue-800">Active Rentals</p>
                            <p class="mt-1 text-2xl font-bold text-blue-900">{{ $activeRentals }}</p>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-green-800">Completed Rentals</p>
                            <p class="mt-1 text-2xl font-bold text-green-900">{{ $completedRentals }}</p>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <p class="text-sm font-medium text-purple-800">Total Rentals</p>
                            <p class="mt-1 text-2xl font-bold text-purple-900">{{ $totalRentals }}</p>
                        </div>
                    </div>
                    
                    @if($user->rentals()->exists())
                        <div>
                            <a href="{{ route('admin.rentals.index', ['user_id' => $user->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                View All User Rentals →
                            </a>
                        </div>
                    @else
                        <div class="text-sm text-gray-500">
                            This user has no rental history.
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Edit User
                </a>
                
                @if(!$user->rentals()->whereIn('status', ['active', 'pending'])->exists())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete User
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection