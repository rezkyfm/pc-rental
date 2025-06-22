@extends('layouts.admin')

@section('title', 'Edit Component Category')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Edit Component Category</h2>
        <a href="{{ route('admin.component-categories.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition">
            Back to Categories
        </a>
    </div>

    @livewire('component-category-form', ['category' => $componentCategory])

    @if($componentCategory->components->count() > 0)
    <div class="mt-10">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Components in this Category</h3>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($componentCategory->components as $component)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $component->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $component->brand }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $component->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $component->status === 'in_use' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $component->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $component->status === 'retired' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ ucfirst($component->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.components.edit', $component) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection