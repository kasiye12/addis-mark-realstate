<?php

if (!function_exists('format_price')) {
    function format_price($price, $type = 'sale')
    {
        if ($type === 'rent') {
            return 'ETB ' . number_format($price) . ' / month';
        }
        return 'ETB ' . number_format($price);
    }
}

if (!function_exists('get_property_status_badge')) {
    function get_property_status_badge($status)
    {
        $badges = [
            'available' => ['bg-green-100 text-green-800', 'Available'],
            'sold' => ['bg-red-100 text-red-800', 'Sold'],
            'rented' => ['bg-blue-100 text-blue-800', 'Rented'],
            'pending' => ['bg-yellow-100 text-yellow-800', 'Pending'],
        ];
        
        return $badges[$status] ?? ['bg-gray-100 text-gray-800', 'Unknown'];
    }
}

if (!function_exists('get_property_type_icon')) {
    function get_property_type_icon($type)
    {
        $icons = [
            'apartment' => 'ri-building-line',
            'villa' => 'ri-home-4-line',
            'commercial' => 'ri-store-line',
            'land' => 'ri-landscape-line',
            'office' => 'ri-briefcase-line',
        ];
        
        return $icons[$type] ?? 'ri-building-line';
    }
}