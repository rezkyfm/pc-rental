@extends('layouts.admin')

@section('title', 'Create Component Category')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Create Component Category</h2>
        <a href="{{ route('admin.component-categories.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition">
            Back to Categories
        </a>
    </div>

    @livewire('component-category-form')
@endsection