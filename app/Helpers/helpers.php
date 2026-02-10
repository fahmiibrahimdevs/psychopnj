<?php

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
