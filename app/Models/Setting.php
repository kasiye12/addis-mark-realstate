<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get setting value by key.
     */
    public static function get($key, $default = null)
    {
        try {
            if (\Schema::hasTable('settings')) {
                $setting = self::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            }
        } catch (\Exception $e) {
            // Table doesn't exist
        }
        return $default;
    }

    /**
     * Set setting value by key.
     */
    public static function set($key, $value, $type = 'text')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}