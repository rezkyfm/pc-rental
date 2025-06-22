<?php

namespace App\Facades;

use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static array getCategory(string $category)
 * @method static string getFormatted(string $key, mixed $default = null, string $format = 'plain')
 * @method static bool isEnabled(string $key, bool $default = false)
 * 
 * @see \App\Helpers\SettingsHelper
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SettingsHelper::class;
    }
}
