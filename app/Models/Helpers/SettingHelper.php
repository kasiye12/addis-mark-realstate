<?php

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('setting_image')) {
    function setting_image($key, $default = null)
    {
        return \App\Models\Setting::getImage($key, $default);
    }
}

if (!function_exists('setting_video')) {
    function setting_video($key)
    {
        return \App\Models\Setting::getVideo($key);
    }
}