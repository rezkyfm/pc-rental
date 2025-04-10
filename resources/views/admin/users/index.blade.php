@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
    <div class="flex justify-end items-center mb-6">
        <a href="{{ route('admin.users.create') }}" class="bg-slate-800 text-white py-2 px-4 rounded-md hover:bg-slate-900 transition">
            Add New User
        </a>
    </div>

    @livewire('user-table')
@endsection