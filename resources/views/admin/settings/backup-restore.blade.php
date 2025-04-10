@extends('layouts.admin')

@section('title', 'Settings Backup & Restore')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Settings Backup & Restore</h1>
        <p class="mt-1 text-sm text-gray-500">Backup your settings or restore from a previous backup.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Backup Settings -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Backup Settings</h2>
            <p class="text-sm text-gray-500 mb-4">
                Export all your application settings as a JSON file. You can use this file to restore your settings later or transfer them to another installation.
            </p>
            
            <div class="mt-6">
                <a href="{{ route('admin.settings.export') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Settings
                </a>
            </div>
        </div>

        <!-- Restore Settings -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Restore Settings</h2>
            <p class="text-sm text-gray-500 mb-4">
                Import settings from a JSON file. This will overwrite any existing settings with the same keys.
                <span class="text-amber-600 font-semibold">Make a backup of your current settings before importing.</span>
            </p>
            
            <form action="{{ route('admin.settings.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                
                <div class="mb-4">
                    <label for="settings_file" class="block text-sm font-medium text-gray-700 mb-1">Settings File (JSON)</label>
                    <input type="file" name="settings_file" id="settings_file" accept=".json,.txt" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    
                    @error('settings_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Import Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Settings -->
    <div class="mt-6 bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Reset to Default</h2>
        <div class="flex items-start">
            <div class="flex-1">
                <p class="text-sm text-gray-500">
                    Reset all settings to their default values. This action cannot be undone.
                    <span class="text-red-600 font-semibold">Make a backup of your current settings before resetting.</span>
                </p>
            </div>
            <div class="ml-4">
                <form action="{{ route('admin.settings.reset') }}" method="POST" onsubmit="return confirm('Are you sure you want to reset all settings to their default values? This action cannot be undone.');">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset to Default
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Back button -->
    <div class="mt-6">
        <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Settings
        </a>
    </div>
@endsection