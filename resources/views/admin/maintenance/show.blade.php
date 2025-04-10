@extends('layouts.admin')

@section('title', 'Maintenance Details')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.maintenance.index') }}" class="text-gray-600 hover:text-gray-900 mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Maintenance Details</h1>
            </div>
            
            <div class="flex space-x-3">
                @if(in_array($maintenance->status, ['scheduled', 'in_progress']))
                    <a href="{{ route('admin.maintenance.edit', $maintenance) }}" 
                        class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                        Edit
                    </a>
                @endif
                
                @if($maintenance->status === 'in_progress')
                    <a href="{{ route('admin.maintenance.show', ['maintenance' => $maintenance, 'complete' => true]) }}" 
                        class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition">
                        Complete
                    </a>
                @endif
                
                @if(in_array($maintenance->status, ['scheduled', 'in_progress']))
                    <form action="{{ route('admin.maintenance.cancel', $maintenance) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition"
                            onclick="return confirm('Are you sure you want to cancel this maintenance?')">
                            Cancel
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(request()->has('complete') && $maintenance->status === 'in_progress')
            <div class="mt-6">
                @livewire('complete-maintenance', ['maintenance' => $maintenance])
            </div>
        @else
            <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $maintenance->title }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Maintenance ID: #{{ $maintenance->id }}
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                PC
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('admin.pcs.show', $maintenance->pc) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $maintenance->pc->name }} ({{ $maintenance->pc->code }})
                                </a>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Type
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($maintenance->type == 'routine')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Routine
                                    </span>
                                @elseif($maintenance->type == 'repair')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Repair
                                    </span>
                                @elseif($maintenance->type == 'upgrade')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Upgrade
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Other
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Status
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($maintenance->status == 'scheduled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Scheduled
                                    </span>
                                @elseif($maintenance->status == 'in_progress')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        In Progress
                                    </span>
                                @elseif($maintenance->status == 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Cancelled
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Performed By
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->performer->name ?? 'N/A' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Scheduled Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->scheduled_date->format('M d, Y h:i A') }}
                            </dd>
                        </div>
                        @if($maintenance->completed_date)
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Completed Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->completed_date->format('M d, Y h:i A') }}
                            </dd>
                        </div>
                        @endif
                        @if($maintenance->cost)
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                Cost
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                            </dd>
                        </div>
                        @endif
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Description
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">
                                {{ $maintenance->description }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">
                                Created
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $maintenance->created_at->format('M d, Y h:i A') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- PC Components Information -->
            <div class="mt-6 bg-white shadow overflow-hidden rounded-lg">
                <div class="px-4 py-5 sm:px-6 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        PC Components
                    </h3>
                </div>
                <div class="border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Component
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Specifications
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Serial
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($maintenance->pc->components as $component)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $component->category->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $component->brand }} {{ $component->model }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ Str::limit($component->specifications, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $component->serial_number ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection