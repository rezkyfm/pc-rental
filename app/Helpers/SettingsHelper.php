<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }

    /**
     * Get all settings by category.
     *
     * @param string $category
     * @return array
     */
    public static function getCategory($category)
    {
        return Setting::getSettingsByPrefix($category);
    }

    /**
     * Get a formatted setting value with appropriate units.
     *
     * @param string $key
     * @param mixed $default
     * @param string $format
     * @return string
     */
    public static function getFormatted($key, $default = null, $format = 'plain')
    {
        $value = self::get($key, $default);

        if ($value === null) {
            return '';
        }

        // Handle currency formatting
        if ($format === 'currency' || str_contains($key, 'price') || str_contains($key, 'amount')) {
            $currencySymbol = self::get('payment.currency_symbol', 'Rp');
            return $currencySymbol . ' ' . number_format($value, 0, ',', '.');
        }

        // Handle percentage
        if ($format === 'percentage' || str_contains($key, 'percentage')) {
            return $value . '%';
        }

        // Handle time duration
        if ($format === 'hours' || str_contains($key, 'hours')) {
            return $value . ' hour' . ($value != 1 ? 's' : '');
        }

        if ($format === 'days' || str_contains($key, 'days')) {
            return $value . ' day' . ($value != 1 ? 's' : '');
        }

        // Return as is for other formats
        return $value;
    }

    /**
     * Get a boolean setting value.
     *
     * @param string $key
     * @param bool $default
     * @return bool
     */
    public static function isEnabled($key, $default = false)
    {
        $value = self::get($key, $default);
        
        if (is_bool($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            return $value === 'true' || $value === '1' || $value === 'yes' || $value === 'on';
        }
        
        return (bool)$value;
    }
}
