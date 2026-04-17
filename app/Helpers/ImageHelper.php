<?php

if (!function_exists('placeholder_image')) {
    function placeholder_image($width = 400, $height = 300, $text = 'Property', $bgColor = '2563eb', $textColor = 'ffffff')
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'">';
        $svg .= '<rect width="'.$width.'" height="'.$height.'" fill="#'.$bgColor.'"/>';
        $svg .= '<text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#'.$textColor.'" font-size="20" font-family="Arial, sans-serif">'.$text.'</text>';
        $svg .= '</svg>';
        
        return 'data:image/svg+xml,' . rawurlencode($svg);
    }
}