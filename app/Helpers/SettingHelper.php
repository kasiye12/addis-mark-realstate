<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        try {
            if (class_exists('App\Models\Setting') && \Schema::hasTable('settings')) {
                return \App\Models\Setting::get($key, $default);
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        return $default;
    }
}

if (!function_exists('setting_image')) {
    function setting_image($key, $default = null)
    {
        $value = setting($key);
        return $value ? asset('storage/' . $value) : $default;
    }
}

if (!function_exists('setting_video')) {
    function setting_video($key)
    {
        $value = setting($key);
        return $value ? asset('storage/' . $value) : null;
    }
}