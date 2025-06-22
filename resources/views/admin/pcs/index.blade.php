@extends('layouts.admin')

@section('title', 'PCs Management')

@section('content')
    <div class="flex justify-end items-center mb-6">
        <a href="{{ route('admin.pcs.create') }}" class="bg-slate-800 text-white py-2 px-4 rounded-md hover:bg-slate-900 transition">
            Add New PC
        </a>
    </div>

    @livewire('p-c-table')
@endsection