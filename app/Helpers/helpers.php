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

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
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

if (!function_exists('mimeTypeFromFilename')) {
    /**
     * Infer MIME type from filename extension without remote storage calls.
     *
     * @param string|null $filename
     * @return string
     */
    function mimeTypeFromFilename($filename)
    {
        $ext = strtolower(pathinfo((string) $filename, PATHINFO_EXTENSION));

        return match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            default => 'application/octet-stream',
        };
    }
}
