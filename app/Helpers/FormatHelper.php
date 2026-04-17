<?php

if (!function_exists('format_phone')) {
    function format_phone($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Format Ethiopian phone numbers
        if (strlen($phone) === 10) {
            return '+251 ' . substr($phone, 1, 2) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
        }
        
        // Format international numbers
        if (strlen($phone) > 10) {
            return '+' . $phone;
        }
        
        return $phone;
    }
}

if (!function_exists('limit_words')) {
    function limit_words($text, $limit = 20)
    {
        $words = explode(' ', $text);
        if (count($words) > $limit) {
            return implode(' ', array_slice($words, 0, $limit)) . '...';
        }
        return $text;
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime)
    {
        return \Carbon\Carbon::parse($datetime)->diffForHumans();
    }
}

if (!function_exists('format_area')) {
    function format_area($area)
    {
        return number_format($area) . ' m²';
    }
}

if (!function_exists('short_number')) {
    function short_number($number)
    {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return $number;
    }
}