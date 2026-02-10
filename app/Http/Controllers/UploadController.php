<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageCompressor;

class UploadController extends Controller
{
    use ImageCompressor;
    
    public function uploadImageSummernote(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png|max:4096',
        ]);

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();

        // Use year-based directory structure
        $year = date('Y');
        $directory = $year . '/image';
        
        // Generate filename
        $originalName = str_replace(' ', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $originalName . '_' . time() . '.' . $ext;

        try {
            // Store file directly using storeAs (native Laravel)
            $path = $file->storeAs($directory, $filename, 'public');
            
            // Full path for compression
            $fullPath = storage_path('app/public/' . $path);
            
            // Get compression setting from .env
            $targetSizeKB = env('IMAGE_COMPRESS_SIZE_KB', 100);
            
            // Compress only if file is larger than target size
            if (file_exists($fullPath)) {
                $currentSizeKB = filesize($fullPath) / 1024;
                
                if ($currentSizeKB > $targetSizeKB) {
                    $this->compressImageToSize($fullPath, $targetSizeKB);
                }
                
                // Check if PNG was converted to JPG
                if ($ext === 'png' && !file_exists($fullPath)) {
                    $path = preg_replace('/\.png$/i', '.jpg', $path);
                    $filename = preg_replace('/\.png$/i', '.jpg', $filename);
                }
            }
            
            // Generate public URL
            $imageUrl = Storage::url($path);

            return response()->json([
                "status" => "success",
                "path" => dirname($path),
                "image" => basename($path),
                "image_url" => $imageUrl
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Upload exception: ' . $e->getMessage());
            return response()->json([
                "status" => "fail",
                "message" => "An error occurred: " . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImageSummernote(Request $request)
    {
        $request->validate([
            'target' => 'required|url'
        ]);

        $urlParts = parse_url($request->target);
        $path = ltrim($urlParts['path'], '/');

        $path = preg_replace('/^storage\//', '', $path);

        if (preg_match('/\.\./', $path)) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid file path."
            ], 400);
        }

        try {
            $deleteStorage = Storage::disk('public')->delete($path);

            if ($deleteStorage) {
                return response()->json([
                    "status" => "success"
                ]);
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => "File not found or could not be deleted."
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "An error occurred: " . $e->getMessage()
            ], 500);
        }
    }
}
