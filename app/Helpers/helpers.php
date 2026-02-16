<?php

if (!function_exists('storageUrl')) {
    /**
     * Get full URL for storage file
     * 
     * @param string|null $path
     * @return string|null
     */
    function storageUrl($path)
    {
        if (!$path) {
            return null;
        }
        
        return asset('storage/' . $path);
    }
}

if (!function_exists('formatRupiah')) {
    /**
     * Format number to Rupiah currency
     * 
     * @param int|float $nominal
     * @return string
     */
    function formatRupiah($nominal)
    {
        return 'Rp ' . number_format($nominal, 0, ',', '.');
    }
}
