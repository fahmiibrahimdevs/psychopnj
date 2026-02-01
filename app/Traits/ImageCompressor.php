<?php

namespace App\Traits;

use Intervention\Image\Laravel\Facades\Image;

trait ImageCompressor
{
    /**
     * Compress image to target size in KB
     * 
     * @param string $sourcePath - Full path to source image
     * @param int $targetSizeKB - Target size in KB (e.g., 100 for 100KB)
     * @param int $maxWidth - Maximum width to resize (default 1920px)
     * @return bool
     */
    public function compressImageToSize($sourcePath, $targetSizeKB = 100, $maxWidth = 1920)
    {
        try {
            $img = Image::read($sourcePath);
            
            // Resize if too large
            if ($img->width() > $maxWidth) {
                $img->scale(width: $maxWidth);
            }
            
            // Get file extension
            $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
            
            // PNG: check if should convert to JPG
            if ($extension === 'png') {
                // Try PNG optimization first
                $tempPath = $sourcePath . '.temp';
                $img->toPng()->save($tempPath);
                
                // Check size
                if (filesize($tempPath) / 1024 <= $targetSizeKB) {
                    rename($tempPath, $sourcePath);
                    return true;
                }
                @unlink($tempPath);
                
                // Convert to JPG for better compression
                $extension = 'jpg';
            }
            
            // Binary search for optimal quality (JPG)
            $quality = 90;
            $minQuality = 10;
            $maxQuality = 95;
            $iterations = 0;
            $maxIterations = 10;
            
            while ($iterations < $maxIterations && abs($maxQuality - $minQuality) > 5) {
                $quality = (int) round(($minQuality + $maxQuality) / 2);
                
                // Save with current quality to temp file
                $tempPath = $sourcePath . '.temp';
                
                if ($extension === 'jpg' || $extension === 'jpeg') {
                    $img->toJpeg($quality)->save($tempPath);
                } else {
                    $img->save($tempPath);
                }
                
                $currentSizeKB = filesize($tempPath) / 1024;
                
                // If within acceptable range (±5%), use it
                if (abs($currentSizeKB - $targetSizeKB) <= ($targetSizeKB * 0.05)) {
                    $finalPath = $sourcePath;
                    
                    // If converted from PNG to JPG, update path
                    if (pathinfo($sourcePath, PATHINFO_EXTENSION) === 'png' && $extension === 'jpg') {
                        $finalPath = preg_replace('/\.png$/i', '.jpg', $sourcePath);
                        @unlink($sourcePath);
                    }
                    
                    rename($tempPath, $finalPath);
                    return true;
                }
                
                // Adjust quality
                if ($currentSizeKB > $targetSizeKB) {
                    $maxQuality = $quality - 1;
                } else {
                    $minQuality = $quality + 1;
                }
                
                @unlink($tempPath);
                $iterations++;
            }
            
            // Final save with last calculated quality
            $finalPath = $sourcePath;
            
            if (pathinfo($sourcePath, PATHINFO_EXTENSION) === 'png' && $extension === 'jpg') {
                $finalPath = preg_replace('/\.png$/i', '.jpg', $sourcePath);
                @unlink($sourcePath);
            }
            
            if ($extension === 'jpg' || $extension === 'jpeg') {
                $img->toJpeg((int) max($quality, 60))->save($finalPath);
            } else {
                $img->save($finalPath);
            }
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error('Image compression failed: ' . $e->getMessage());
            return false;
        }
    }
}
