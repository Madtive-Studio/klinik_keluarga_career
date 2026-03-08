<?php

if(!function_exists('generateFileName')) {
    function generateFileName($prefix, $extension) {
        return $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
    }
}

if(!function_exists('getPlaceholderFile')) {
    function getPlaceholderFilePath($fileName) {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        switch($extension) {
            case 'pdf':
                $placeholder = 'pdf-placeholder.png'; break;
            case 'docx':
            case 'doc':
                $placeholder = 'word-placeholder.png'; break;
            case 'xlsx':
            case 'xls':
                $placeholder = 'excel-placeholder.png'; break;
            case 'png':
            case 'jpg':
            case 'jpeg':
                $placeholder = 'image-placeholder.png'; break;
            default:
                $placeholder = 'others-placeholder.png'; break;
        }

        return 'assets/images/placeholders/' . $placeholder;
    }
}