@extends('layouts.admin')

@section('title', 'Theme Settings')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Theme Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Customize the appearance of your application.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.settings.theme.save') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Primary Color -->
                <div>
                    <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Primary Color</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="color" name="primary_color" id="primary_color" 
                            value="{{ old('primary_color', \App\Models\Setting::getValue('theme.primary_color', '#3b82f6')) }}"
                            class="h-10 rounded-l-md border-r-0 border-gray-300">
                        <input type="text" name="primary_color_text" id="primary_color_text" 
                            value="{{ old('primary_color', \App\Models\Setting::getValue('theme.primary_color', '#3b82f6')) }}"
                            class="flex-1 rounded-r-md border-l-0 border-gray-300"
                            oninput="document.getElementById('primary_color').value = this.value">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        The main color used for buttons, links, and active elements.
                    </p>
                    @error('primary_color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Secondary Color -->
                <div>
                    <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Secondary Color</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="color" name="secondary_color" id="secondary_color" 
                            value="{{ old('secondary_color', \App\Models\Setting::getValue('theme.secondary_color', '#4b5563')) }}"
                            class="h-10 rounded-l-md border-r-0 border-gray-300">
                        <input type="text" name="secondary_color_text" id="secondary_color_text" 
                            value="{{ old('secondary_color', \App\Models\Setting::getValue('theme.secondary_color', '#4b5563')) }}"
                            class="flex-1 rounded-r-md border-l-0 border-gray-300"
                            oninput="document.getElementById('secondary_color').value = this.value">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Used for secondary buttons, borders, and background elements.
                    </p>
                    @error('secondary_color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Font Family -->
                <div>
                    <label for="font_family" class="block text-sm font-medium text-gray-700 mb-1">Font Family</label>
                    <select name="font_family" id="font_family" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="Inter" {{ old('font_family', \App\Models\Setting::getValue('theme.font_family', 'Inter')) === 'Inter' ? 'selected' : '' }}>Inter</option>
                        <option value="Roboto" {{ old('font_family', \App\Models\Setting::getValue('theme.font_family', 'Inter')) === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                        <option value="Poppins" {{ old('font_family', \App\Models\Setting::getValue('theme.font_family', 'Inter')) === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                        <option value="Montserrat" {{ old('font_family', \App\Models\Setting::getValue('theme.font_family', 'Inter')) === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                        <option value="Open Sans" {{ old('font_family', \App\Models\Setting::getValue('theme.font_family', 'Inter')) === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        The main font used throughout the application.
                    </p>
                    @error('font_family')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Sidebar Style -->
                <div>
                    <label for="sidebar_style" class="block text-sm font-medium text-gray-700 mb-1">Sidebar Style</label>
                    <select name="sidebar_style" id="sidebar_style" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="dark" {{ old('sidebar_style', \App\Models\Setting::getValue('theme.sidebar_style', 'dark')) === 'dark' ? 'selected' : '' }}>Dark</option>
                        <option value="light" {{ old('sidebar_style', \App\Models\Setting::getValue('theme.sidebar_style', 'dark')) === 'light' ? 'selected' : '' }}>Light</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        The color scheme for the sidebar navigation.
                    </p>
                    @error('sidebar_style')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Logo Upload -->
                <div class="col-span-full">
                    <label for="logo_path" class="block text-sm font-medium text-gray-700 mb-1">Company Logo</label>
                    <div class="mt-1 flex items-center">
                        <div class="p-2 border border-gray-300 rounded-md bg-gray-50 mr-4">
                            @if(\App\Models\Setting::getValue('theme.logo_path'))
                                <img src="{{ asset(\App\Models\Setting::getValue('theme.logo_path')) }}" 
                                    alt="Company Logo" class="h-12 max-w-[200px] object-contain">
                            @else
                                <div class="h-12 w-36 flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <input type="file" name="logo_path" id="logo_path" accept=".jpg,.jpeg,.png,.gif,.svg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Upload your company logo. Recommended size: 200x50 pixels.
                    </p>
                    @error('logo_path')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-8 pt-4 border-t border-gray-200 flex justify-between">
                <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
                
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Theme Settings
                </button>
            </div>
        </form>
    </div>
    
    <!-- Theme Preview -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Theme Preview</h2>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex flex-col space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Primary Color Preview -->
                    <div>
                        <h3 class="text-md font-medium mb-2">Primary Color</h3>
                        <div class="flex flex-wrap gap-2">
                            <button id="preview-primary-button" class="px-4 py-2 rounded font-medium text-white">
                                Button
                            </button>
                            <div id="preview-primary-badge" class="rounded-full px-3 py-1 text-sm font-medium text-white">
                                Badge
                            </div>
                            <div id="preview-primary-bar" class="w-full h-2 rounded"></div>
                        </div>
                    </div>
                    
                    <!-- Secondary Color Preview -->
                    <div>
                        <h3 class="text-md font-medium mb-2">Secondary Color</h3>
                        <div class="flex flex-wrap gap-2">
                            <button id="preview-secondary-button" class="px-4 py-2 rounded font-medium text-white">
                                Button
                            </button>
                            <div id="preview-secondary-badge" class="rounded-full px-3 py-1 text-sm font-medium text-white">
                                Badge
                            </div>
                            <div id="preview-secondary-bar" class="w-full h-2 rounded"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Font Preview -->
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-md font-medium mb-2">Font Preview</h3>
                    <div id="preview-font" class="space-y-2">
                        <h4 class="text-lg font-semibold">Heading Text</h4>
                        <p>This is a sample paragraph displaying the selected font. The quick brown fox jumps over the lazy dog.</p>
                        <p class="text-sm">This is smaller text in the same font family.</p>
                    </div>
                </div>
                
                <!-- Sidebar Preview -->
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-md font-medium mb-2">Sidebar Preview</h3>
                    
                    <div class="w-full overflow-hidden rounded-lg border border-gray-200">
                        <div id="preview-sidebar" class="w-full h-24 flex items-center px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                                <span class="ml-3 font-medium">Dashboard</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Update theme preview on change
    document.addEventListener('DOMContentLoaded', function() {
        const primaryColorInput = document.getElementById('primary_color');
        const primaryColorTextInput = document.getElementById('primary_color_text');
        const secondaryColorInput = document.getElementById('secondary_color');
        const secondaryColorTextInput = document.getElementById('secondary_color_text');
        const fontFamilySelect = document.getElementById('font_family');
        const sidebarStyleSelect = document.getElementById('sidebar_style');
        
        // Preview elements
        const previewPrimaryButton = document.getElementById('preview-primary-button');
        const previewPrimaryBadge = document.getElementById('preview-primary-badge');
        const previewPrimaryBar = document.getElementById('preview-primary-bar');
        const previewSecondaryButton = document.getElementById('preview-secondary-button');
        const previewSecondaryBadge = document.getElementById('preview-secondary-badge');
        const previewSecondaryBar = document.getElementById('preview-secondary-bar');
        const previewFont = document.getElementById('preview-font');
        const previewSidebar = document.getElementById('preview-sidebar');
        
        // Update on color change
        function updateColorPreview() {
            const primaryColor = primaryColorInput.value;
            const secondaryColor = secondaryColorInput.value;
            
            // Sync text inputs
            primaryColorTextInput.value = primaryColor;
            secondaryColorTextInput.value = secondaryColor;
            
            // Primary color elements
            previewPrimaryButton.style.backgroundColor = primaryColor;
            previewPrimaryBadge.style.backgroundColor = primaryColor;
            previewPrimaryBar.style.backgroundColor = primaryColor;
            
            // Secondary color elements
            previewSecondaryButton.style.backgroundColor = secondaryColor;
            previewSecondaryBadge.style.backgroundColor = secondaryColor;
            previewSecondaryBar.style.backgroundColor = secondaryColor;
        }
        
        // Update on font change
        function updateFontPreview() {
            const fontFamily = fontFamilySelect.value;
            previewFont.style.fontFamily = fontFamily;
        }
        
        // Update on sidebar style change
        function updateSidebarPreview() {
            const sidebarStyle = sidebarStyleSelect.value;
            
            if (sidebarStyle === 'dark') {
                previewSidebar.style.backgroundColor = '#1f2937'; // Dark gray
                previewSidebar.style.color = '#ffffff'; // White
            } else {
                previewSidebar.style.backgroundColor = '#ffffff'; // White
                previewSidebar.style.color = '#1f2937'; // Dark gray
            }
        }
        
        // Register event listeners
        primaryColorInput.addEventListener('input', updateColorPreview);
        primaryColorTextInput.addEventListener('input', function() {
            primaryColorInput.value = this.value;
            updateColorPreview();
        });
        
        secondaryColorInput.addEventListener('input', updateColorPreview);
        secondaryColorTextInput.addEventListener('input', function() {
            secondaryColorInput.value = this.value;
            updateColorPreview();
        });
        
        fontFamilySelect.addEventListener('change', updateFontPreview);
        sidebarStyleSelect.addEventListener('change', updateSidebarPreview);
        
        // Initialize preview
        updateColorPreview();
        updateFontPreview();
        updateSidebarPreview();
    });
</script>
@endpush