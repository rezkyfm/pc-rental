<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Str;

class SettingsManager extends Component
{
    public $generalSettings = [];
    public $paymentSettings = [];
    public $rentalSettings = [];
    public $businessSettings = [];
    public $emailSettings = [];
    
    public $newSettingKey = '';
    public $newSettingValue = '';
    public $newSettingDescription = '';
    public $newSettingGroup = 'general';
    
    public $editMode = false;
    public $settingToEdit = null;

    protected $rules = [
        'generalSettings.*.value' => 'nullable',
        'paymentSettings.*.value' => 'nullable',
        'rentalSettings.*.value' => 'nullable',
        'businessSettings.*.value' => 'nullable',
        'emailSettings.*.value' => 'nullable',
        'newSettingKey' => 'required|string|max:255|unique:settings,key',
        'newSettingValue' => 'nullable',
        'newSettingDescription' => 'nullable|string',
        'newSettingGroup' => 'required|in:general,payment,rental,business,email',
    ];

    protected $messages = [
        'newSettingKey.required' => 'The setting key is required.',
        'newSettingKey.unique' => 'This setting key already exists.',
        'newSettingGroup.required' => 'Please select a setting group.',
    ];

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Get all settings and group them
        $allSettings = Setting::orderBy('key')->get();
        
        $this->generalSettings = $allSettings->filter(function ($setting) {
            return Str::startsWith($setting->key, 'general.');
        })->toArray();
        
        $this->paymentSettings = $allSettings->filter(function ($setting) {
            return Str::startsWith($setting->key, 'payment.');
        })->toArray();
        
        $this->rentalSettings = $allSettings->filter(function ($setting) {
            return Str::startsWith($setting->key, 'rental.');
        })->toArray();
        
        $this->businessSettings = $allSettings->filter(function ($setting) {
            return Str::startsWith($setting->key, 'business.');
        })->toArray();
        
        $this->emailSettings = $allSettings->filter(function ($setting) {
            return Str::startsWith($setting->key, 'email.');
        })->toArray();
    }

    public function saveSettings()
    {
        // Validate existing settings (optional validation for existing settings)
        $this->validate([
            'generalSettings.*.value' => 'nullable',
            'paymentSettings.*.value' => 'nullable',
            'rentalSettings.*.value' => 'nullable',
            'businessSettings.*.value' => 'nullable',
            'emailSettings.*.value' => 'nullable',
        ]);

        // Save general settings
        foreach ($this->generalSettings as $setting) {
            Setting::setValue($setting['key'], $setting['value']);
        }
        
        // Save payment settings
        foreach ($this->paymentSettings as $setting) {
            Setting::setValue($setting['key'], $setting['value']);
        }
        
        // Save rental settings
        foreach ($this->rentalSettings as $setting) {
            Setting::setValue($setting['key'], $setting['value']);
        }
        
        // Save business settings
        foreach ($this->businessSettings as $setting) {
            Setting::setValue($setting['key'], $setting['value']);
        }
        
        // Save email settings
        foreach ($this->emailSettings as $setting) {
            Setting::setValue($setting['key'], $setting['value']);
        }

        // Reload settings to get fresh data
        $this->loadSettings();

        // Show success message
        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Settings have been saved successfully!',
            'icon' => 'success',
        ]);
    }

    public function addNewSetting()
    {
        // Validate new setting
        $this->validate([
            'newSettingKey' => 'required|string|max:255|unique:settings,key',
            'newSettingValue' => 'nullable',
            'newSettingDescription' => 'nullable|string',
            'newSettingGroup' => 'required|in:general,payment,rental,business,email',
        ]);

        // Prepend the group to the key if not already present
        $key = $this->newSettingKey;
        if (!Str::startsWith($key, $this->newSettingGroup . '.')) {
            $key = $this->newSettingGroup . '.' . $key;
        }

        // Create new setting
        Setting::setValue($key, $this->newSettingValue, $this->newSettingDescription);

        // Reset form
        $this->newSettingKey = '';
        $this->newSettingValue = '';
        $this->newSettingDescription = '';
        $this->newSettingGroup = 'general';

        // Reload settings to include the new one
        $this->loadSettings();

        // Show success message
        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'New setting has been added successfully!',
            'icon' => 'success',
        ]);
    }

    public function startEditing($settingId)
    {
        $this->settingToEdit = Setting::findOrFail($settingId);
        $this->editMode = true;
    }

    public function cancelEditing()
    {
        $this->settingToEdit = null;
        $this->editMode = false;
    }

    public function updateSetting()
    {
        // Validate
        $this->validate([
            'settingToEdit.value' => 'nullable',
            'settingToEdit.description' => 'nullable|string',
        ]);

        // Update setting
        $this->settingToEdit->save();

        // Reset edit mode
        $this->settingToEdit = null;
        $this->editMode = false;

        // Reload settings
        $this->loadSettings();

        // Show success message
        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Setting has been updated successfully!',
            'icon' => 'success',
        ]);
    }

    public function confirmDelete($settingId)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Are you sure?',
            'text' => "This setting will be permanently deleted!",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#d33',
            'cancelButtonColor' => '#3085d6',
            'confirmButtonText' => 'Yes, delete it!',
            'id' => $settingId
        ]);
    }

    public function deleteSetting($settingId)
    {
        $setting = Setting::findOrFail($settingId);
        $setting->delete();

        // Reload settings
        $this->loadSettings();

        // Show success message
        $this->dispatch('swal:success', [
            'title' => 'Deleted!',
            'text' => 'Setting has been deleted successfully!',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.settings-manager');
    }
}
