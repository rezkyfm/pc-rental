<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    /**
     * Clear the settings cache safely.
     */
    public static function clearCache()
    {
        try {
            if (app()->bound('cache')) {
                Cache::forget('application_settings');
            }
        } catch (\Exception $e) {
            // Ignore cache errors
        }
    }

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($key, $default = null)
    {
        try {
            // Try to get from cache first
            if (app()->bound('cache') && Cache::has('application_settings')) {
                $settings = Cache::get('application_settings');
                if (isset($settings[$key])) {
                    return self::castValue($settings[$key]);
                }
            }
        } catch (\Exception $e) {
            // Ignore cache errors
        }

        $setting = self::where('key', $key)->first();
        return $setting ? self::castValue($setting->value) : $default;
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $description
     * @return Setting
     */
    public static function setValue($key, $value, $description = null)
    {
        $setting = self::firstOrNew(['key' => $key]);
        $setting->value = $value;

        if ($description && !$setting->exists) {
            $setting->description = $description;
        } else if ($description) {
            // Only update description if provided and the setting already exists
            $setting->description = $description;
        }

        $setting->save();

        // Clear the cache safely
        self::clearCache();

        return $setting;
    }

    /**
     * Get all settings as an associative array.
     *
     * @return array
     */
    public static function getAllSettings()
    {
        try {
            // Try to get from cache first
            if (app()->bound('cache') && Cache::has('application_settings')) {
                return Cache::get('application_settings');
            }
        } catch (\Exception $e) {
            // Ignore cache errors
        }

        // Get from database
        $settings = self::all()->pluck('value', 'key')->toArray();
        
        try {
            // Cache for 24 hours if cache is available
            if (app()->bound('cache')) {
                Cache::put('application_settings', $settings, 60 * 24);
            }
        } catch (\Exception $e) {
            // Ignore cache errors
        }

        return $settings;
    }

    /**
     * Cast setting value to appropriate type.
     *
     * @param string $value
     * @return mixed
     */
    protected static function castValue($value)
    {
        // Boolean conversion
        if ($value === 'true') {
            return true;
        } elseif ($value === 'false') {
            return false;
        }

        // Numeric conversion
        if (is_numeric($value)) {
            // Integer
            if ((int)$value == $value) {
                return (int)$value;
            }
            // Float
            if ((float)$value == $value) {
                return (float)$value;
            }
        }

        // JSON conversion if it's a valid JSON string
        if (is_string($value) && (
            (substr($value, 0, 1) === '{' && substr($value, -1) === '}') || 
            (substr($value, 0, 1) === '[' && substr($value, -1) === ']')
        )) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        // Return as is for other types
        return $value;
    }

    /**
     * Get a group of settings by prefix.
     * 
     * @param string $prefix
     * @return array
     */
    public static function getSettingsByPrefix($prefix)
    {
        $prefix = rtrim($prefix, '.');
        $allSettings = self::getAllSettings();

        $filteredSettings = [];
        foreach ($allSettings as $key => $value) {
            if (strpos($key, $prefix . '.') === 0) {
                // Get the part after the prefix
                $shortKey = substr($key, strlen($prefix) + 1);
                $filteredSettings[$shortKey] = self::castValue($value);
            }
        }

        return $filteredSettings;
    }

    /**
     * Delete a setting by key.
     *
     * @param string $key
     * @return bool
     */
    public static function deleteByKey($key)
    {
        $result = self::where('key', $key)->delete();
        
        // Clear the cache safely
        self::clearCache();
        
        return $result;
    }
}
