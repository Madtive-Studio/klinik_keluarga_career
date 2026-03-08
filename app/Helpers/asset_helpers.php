<?php

if(!function_exists('generateFileName')) {
    function generateFileName($prefix, $extension) {
        return $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
    }
}