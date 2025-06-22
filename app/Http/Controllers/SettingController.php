<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display and edit application settings.
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Update application settings.
     */
    public function update(Request $request)
    {
        try {
            $inputs = $request->except('_token', '_method');

            foreach ($inputs as $key => $value) {
                Setting::setValue($key, $value);
            }

            // Clear settings cache
            Cache::forget('application_settings');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating settings: ' . $e->getMessage());
            
            return redirect()->route('admin.settings.index')
                ->with('error', 'There was an error updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Export settings as JSON.
     */
    public function export()
    {
        try {
            $settings = Setting::all()->pluck('value', 'key')->toArray();
            
            $jsonSettings = json_encode($settings, JSON_PRETTY_PRINT);
            
            $filename = 'pc_rental_settings_' . date('Y-m-d_His') . '.json';
            
            return response()->streamDownload(function () use ($jsonSettings) {
                echo $jsonSettings;
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting settings: ' . $e->getMessage());
            
            return redirect()->route('admin.settings.index')
                ->with('error', 'There was an error exporting settings: ' . $e->getMessage());
        }
    }

    /**
     * Import settings from JSON.
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'settings_file' => 'required|file|mimes:json,txt|max:2048',
            ]);
            
            $file = $request->file('settings_file');
            $content = file_get_contents($file->getPathname());
            $settings = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->route('admin.settings.index')
                    ->with('error', 'Invalid JSON file format.');
            }
            
            foreach ($settings as $key => $value) {
                Setting::setValue($key, $value);
            }
            
            // Clear settings cache
            Cache::forget('application_settings');
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings imported successfully!');
        } catch (\Exception $e) {
            Log::error('Error importing settings: ' . $e->getMessage());
            
            return redirect()->route('admin.settings.index')
                ->with('error', 'There was an error importing settings: ' . $e->getMessage());
        }
    }

    /**
     * Reset settings to default values.
     */
    public function reset()
    {
        try {
            // Define default settings
            $defaultSettings = [
                'general.site_name' => 'PC Rental Service',
                'general.site_description' => 'Rent high-quality PCs for your needs',
                'general.contact_email' => 'contact@pcrental.com',
                'general.contact_phone' => '+1234567890',
                
                'payment.currency' => 'IDR',
                'payment.currency_symbol' => 'Rp',
                'payment.deposit_percentage' => '30',
                'payment.tax_percentage' => '10',
                
                'rental.min_rental_hours' => '1',
                'rental.max_rental_days' => '30',
                'rental.business_hours_start' => '09:00',
                'rental.business_hours_end' => '21:00',
                
                'business.company_name' => 'PC Rental, Inc.',
                'business.address' => '123 Main Street',
                'business.city' => 'Any City',
                'business.postal_code' => '12345',
                'business.country' => 'Indonesia',
                
                'email.admin_notification' => 'admin@pcrental.com',
                'email.send_rental_confirmation' => 'true',
            ];
            
            // Delete all existing settings
            Setting::truncate();
            
            // Insert default settings
            foreach ($defaultSettings as $key => $value) {
                $description = ucwords(str_replace('_', ' ', explode('.', $key)[1]));
                Setting::setValue($key, $value, $description);
            }
            
            // Clear settings cache
            Cache::forget('application_settings');
            
            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings have been reset to default values.');
        } catch (\Exception $e) {
            Log::error('Error resetting settings: ' . $e->getMessage());
            
            return redirect()->route('admin.settings.index')
                ->with('error', 'There was an error resetting settings: ' . $e->getMessage());
        }
    }

    /**
     * Display settings backup/restore page.
     */
    public function backupRestore()
    {
        return view('admin.settings.backup-restore');
    }
    
    /**
     * Display theme settings page.
     */
    public function theme()
    {
        return view('admin.settings.theme');
    }
    
    /**
     * Save theme settings.
     */
    public function saveTheme(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'primary_color' => 'required|string|size:7|starts_with:#',
                'secondary_color' => 'required|string|size:7|starts_with:#',
                'font_family' => 'required|string|max:100',
                'sidebar_style' => 'required|in:dark,light',
                'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Handle logo upload if present
            if ($request->hasFile('logo_path')) {
                $logoFile = $request->file('logo_path');
                $logoName = 'logo.' . $logoFile->getClientOriginalExtension();
                $logoFile->move(public_path('images'), $logoName);
                
                Setting::setValue('theme.logo_path', 'images/' . $logoName, 'Logo Path');
            }
            
            // Save theme settings
            Setting::setValue('theme.primary_color', $request->primary_color, 'Primary Color');
            Setting::setValue('theme.secondary_color', $request->secondary_color, 'Secondary Color');
            Setting::setValue('theme.font_family', $request->font_family, 'Font Family');
            Setting::setValue('theme.sidebar_style', $request->sidebar_style, 'Sidebar Style');
            
            // Clear settings cache
            Cache::forget('application_settings');
            
            return redirect()->route('admin.settings.theme')
                ->with('success', 'Theme settings saved successfully!');
        } catch (\Exception $e) {
            Log::error('Error saving theme settings: ' . $e->getMessage());
            
            return redirect()->route('admin.settings.theme')
                ->with('error', 'There was an error saving theme settings: ' . $e->getMessage());
        }
    }
    
    /**
     * Display notification settings page.
     */
    public function notifications()
    {
        return view('admin.settings.notifications');
    }
    
    /**
     * Save notification settings.
     */
    public function saveNotifications(Request $request)
    {
        try {
            $inputs = $request->except('_token', '_method');
            
            foreach ($inputs as $key => $value) {
                // Convert checkboxes to boolean strings
                if ($value === 'on') {
                    $value = 'true';
                } elseif (empty($value) && $request->has($key)) {
                    $value = 'false';
                }
                
                Setting::setValue('notification.' . $key, $value);
            }
            
            // Clear settings cache
            Cache::forget('application_settings');
            
            return redirect()->route('admin.settings.notifications')
                ->with('success', 'Notification settings saved successfully!');
        } catch (\Exception $e) {
            Log::error('Error saving notification settings: ' . $e->getMessage());
            
            return redirect()->route('admin.settings.notifications')
                ->with('error', 'There was an error saving notification settings: ' . $e->getMessage());
        }
    }
}
