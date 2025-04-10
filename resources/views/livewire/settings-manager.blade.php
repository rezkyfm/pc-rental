<div>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" x-data="{ activeTab: 'general' }">
                <button @click="activeTab = 'general'" :class="{'text-blue-600 border-blue-500': activeTab === 'general', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'general'}" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        General
                    </div>
                </button>
                <button @click="activeTab = 'payment'" :class="{'text-blue-600 border-blue-500': activeTab === 'payment', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'payment'}" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Payment
                    </div>
                </button>
                <button @click="activeTab = 'rental'" :class="{'text-blue-600 border-blue-500': activeTab === 'rental', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'rental'}" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Rental
                    </div>
                </button>
                <button @click="activeTab = 'business'" :class="{'text-blue-600 border-blue-500': activeTab === 'business', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'business'}" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Business
                    </div>
                </button>
                <button @click="activeTab = 'email'" :class="{'text-blue-600 border-blue-500': activeTab === 'email', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'email'}" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email
                    </div>
                </button>
                <button @click="activeTab = 'new'" :class="{'text-blue-600 border-blue-500': activeTab === 'new', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'new'}" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Add New
                    </div>
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            <!-- General Settings -->
            <div x-data="{ activeTab: 'general' }" x-show="activeTab === 'general'">
                <h3 class="text-lg font-medium text-gray-900 mb-4">General Settings</h3>
                
                @if(count($generalSettings) > 0)
                    <div class="space-y-4">
                        @foreach($generalSettings as $index => $setting)
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <label for="generalSettings.{{ $index }}.value" class="block text-sm font-medium text-gray-700">
                                        {{ str_replace('general.', '', $setting['key']) }}
                                        @if($setting['description'])
                                            <span class="text-xs text-gray-500">
                                                ({{ $setting['description'] }})
                                            </span>
                                        @endif
                                    </label>
                                    <input wire:model="generalSettings.{{ $index }}.value" type="text" id="generalSettings.{{ $index }}.value" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div class="ml-4 flex-shrink-0">
                                    <button wire:click="confirmDelete({{ $setting['id'] }})" class="text-red-500 hover:text-red-700" title="Delete Setting">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        No general settings found. Add some from the "Add New" tab.
                    </div>
                @endif
            </div>

            <!-- Payment Settings -->
            <div x-data="{ activeTab: 'general' }" x-show="activeTab === 'payment'">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Settings</h3>
                
                @if(count($paymentSettings) > 0)
                    <div class="space-y-4">
                        @foreach($paymentSettings as $index => $setting)
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <label for="paymentSettings.{{ $index }}.value" class="block text-sm font-medium text-gray-700">
                                        {{ str_replace('payment.', '', $setting['key']) }}
                                        @if($setting['description'])
                                            <span class="text-xs text-gray-500">
                                                ({{ $setting['description'] }})
                                            </span>
                                        @endif
                                    </label>
                                    <input wire:model="paymentSettings.{{ $index }}.value" type="text" id="paymentSettings.{{ $index }}.value" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div class="ml-4 flex-shrink-0">
                                    <button wire:click="confirmDelete({{ $setting['id'] }})" class="text-red-500 hover:text-red-700" title="Delete Setting">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        No payment settings found. Add some from the "Add New" tab.
                    </div>
                @endif
            </div>

            <!-- Rental Settings -->
            <div x-data="{ activeTab: 'general' }" x-show="activeTab === 'rental'">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Rental Settings</h3>
                
                @if(count($rentalSettings) > 0)
                    <div class="space-y-4">
                        @foreach($rentalSettings as $index => $setting)
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <label for="rentalSettings.{{ $index }}.value" class="block text-sm font-medium text-gray-700">
                                        {{ str_replace('rental.', '', $setting['key']) }}
                                        @if($setting['description'])
                                            <span class="text-xs text-gray-500">
                                                ({{ $setting['description'] }})
                                            </span>
                                        @endif
                                    </label>
                                    <input wire:model="rentalSettings.{{ $index }}.value" type="text" id="rentalSettings.{{ $index }}.value" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div class="ml-4 flex-shrink-0">
                                    <button wire:click="confirmDelete({{ $setting['id'] }})" class="text-red-500 hover:text-red-700" title="Delete Setting">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        No rental settings found. Add some from the "Add New" tab.
                    </div>
                @endif
            </div>

            <!-- Business Settings -->
            <div x-data="{ activeTab: 'general' }" x-show="activeTab === 'business'">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Business Settings</h3>
                
                @if(count($businessSettings) > 0)
                    <div class="space-y-4">
                        @foreach($businessSettings as $index => $setting)
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <label for="businessSettings.{{ $index }}.value" class="block text-sm font-medium text-gray-700">
                                        {{ str_replace('business.', '', $setting['key']) }}
                                        @if($setting['description'])
                                            <span class="text-xs text-gray-500">
                                                ({{ $setting['description'] }})
                                            </span>
                                        @endif
                                    </label>
                                    <input wire:model="businessSettings.{{ $index }}.value" type="text" id="businessSettings.{{ $index }}.value" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div class="ml-4 flex-shrink-0">
                                    <button wire:click="confirmDelete({{ $setting['id'] }})" class="text-red-500 hover:text-red-700" title="Delete Setting">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        No business settings found. Add some from the "Add New" tab.
                    </div>
                @endif
            </div>

            <!-- Email Settings -->
            <div x-data="{ activeTab: 'general' }" x-show="activeTab === 'email'">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Email Settings</h3>
                
                @if(count($emailSettings) > 0)
                    <div class="space-y-4">
                        @foreach($emailSettings as $index => $setting)
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <label for="emailSettings.{{ $index }}.value" class="block text-sm font-medium text-gray-700">
                                        {{ str_replace('email.', '', $setting['key']) }}
                                        @if($setting['description'])
                                            <span class="text-xs text-gray-500">
                                                ({{ $setting['description'] }})
                                            </span>
                                        @endif
                                    </label>
                                    <input wire:model="emailSettings.{{ $index }}.value" type="text" id="emailSettings.{{ $index }}.value" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div class="ml-4 flex-shrink-0">
                                    <button wire:click="confirmDelete({{ $setting['id'] }})" class="text-red-500 hover:text-red-700" title="Delete Setting">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-gray-500">
                        No email settings found. Add some from the "Add New" tab.
                    </div>
                @endif
            </div>

            <!-- Add New Setting -->
            <div x-data="{ activeTab: 'general' }" x-show="activeTab === 'new'">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Setting</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="newSettingGroup" class="block text-sm font-medium text-gray-700">Setting Group <span class="text-red-500">*</span></label>
                        <select wire:model="newSettingGroup" id="newSettingGroup" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="general">General</option>
                            <option value="payment">Payment</option>
                            <option value="rental">Rental</option>
                            <option value="business">Business</option>
                            <option value="email">Email</option>
                        </select>
                        @error('newSettingGroup')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="newSettingKey" class="block text-sm font-medium text-gray-700">Setting Key <span class="text-red-500">*</span></label>
                        <input wire:model="newSettingKey" type="text" id="newSettingKey" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="e.g. site_name">
                        <p class="mt-1 text-xs text-gray-500">Will be stored as "{{ $newSettingGroup }}.{{ $newSettingKey }}"</p>
                        @error('newSettingKey')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="newSettingValue" class="block text-sm font-medium text-gray-700">Value</label>
                        <input wire:model="newSettingValue" type="text" id="newSettingValue"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('newSettingValue')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="newSettingDescription" class="block text-sm font-medium text-gray-700">Description</label>
                        <input wire:model="newSettingDescription" type="text" id="newSettingDescription"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="What is this setting for?">
                        @error('newSettingDescription')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <button wire:click="addNewSetting" type="button" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                        Add Setting
                    </button>
                </div>
            </div>
        </div>

        <!-- Edit Setting Modal -->
        @if($editMode && $settingToEdit)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Edit Setting
                                    </h3>
                                    
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="settingToEdit.key" class="block text-sm font-medium text-gray-700">Key</label>
                                            <input type="text" id="settingToEdit.key" value="{{ $settingToEdit->key }}" disabled
                                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                        </div>
                                        
                                        <div>
                                            <label for="settingToEdit.value" class="block text-sm font-medium text-gray-700">Value</label>
                                            <input wire:model="settingToEdit.value" type="text" id="settingToEdit.value"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        
                                        <div>
                                            <label for="settingToEdit.description" class="block text-sm font-medium text-gray-700">Description</label>
                                            <input wire:model="settingToEdit.description" type="text" id="settingToEdit.description"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="updateSetting" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Save Changes
                            </button>
                            <button wire:click="cancelEditing" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Save Button -->
    <div class="mt-6">
        <div class="flex justify-end">
            <button wire:click="saveSettings" type="button" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                Save All Settings
            </button>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Confirmation Dialog
            @this.on('swal:confirm', (event) => {
                const data = event[0];

                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    showCancelButton: data.showCancelButton,
                    confirmButtonColor: data.confirmButtonColor,
                    cancelButtonColor: data.cancelButtonColor,
                    confirmButtonText: data.confirmButtonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteSetting(data.id);
                    }
                });
            });

            // Success Alert
            @this.on('swal:success', (event) => {
                const data = event[0];

                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    confirmButtonColor: '#3085d6'
                });
            });

            // Error Alert
            @this.on('swal:error', (event) => {
                const data = event[0];

                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    confirmButtonColor: '#3085d6'
                });
            });
        });
    </script>
</div>