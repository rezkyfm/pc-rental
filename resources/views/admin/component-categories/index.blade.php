@extends('layouts.admin')

@section('title', 'Component Categories')

@section('content')
    <div class="flex justify-end items-center mb-6">
        <a href="{{ route('admin.component-categories.create') }}" class="bg-slate-800 text-white py-2 px-4 rounded-md hover:bg-slate-900 transition">
            Add New Category
        </a>
    </div>

    @livewire('component-category-table')
@endsection