<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
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
        $baseFileName = $originalName . '_' . time();

        try {
            $path = $this->uploadOptimizedImageToPublicDisk($file, $directory, $baseFileName);
            
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

        $r2Root = trim((string) env('R2_ROOT', ''), '/');
        if ($r2Root !== '' && str_starts_with($path, $r2Root.'/')) {
            $path = substr($path, strlen($r2Root) + 1);
        }

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

    private function uploadOptimizedImageToPublicDisk(UploadedFile $file, string $directory, string $baseFileName): string
    {
        $tempDir = storage_path('app/livewire-tmp/processed');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $sourceExt = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $tempPath = $tempDir.'/'.uniqid('img_', true).'.'.$sourceExt;

        if (!copy($file->getRealPath(), $tempPath)) {
            throw new \RuntimeException('Failed to copy uploaded file to temporary path.');
        }

        $targetSizeKB = env('IMAGE_COMPRESS_SIZE_KB', 100);
        $currentSizeKB = filesize($tempPath) / 1024;
        if ($currentSizeKB > $targetSizeKB) {
            $this->compressImageToSize($tempPath, $targetSizeKB);
        }

        $processedPath = $tempPath;
        if (!file_exists($processedPath)) {
            $jpgPath = preg_replace('/\.png$/i', '.jpg', $tempPath);
            if ($jpgPath && file_exists($jpgPath)) {
                $processedPath = $jpgPath;
            } else {
                throw new \RuntimeException('Processed temporary image file not found.');
            }
        }

        $finalExt = strtolower(pathinfo($processedPath, PATHINFO_EXTENSION));
        $relativePath = trim($directory, '/').'/'.$baseFileName.'.'.$finalExt;

        $stream = fopen($processedPath, 'r');
        if ($stream === false) {
            throw new \RuntimeException('Failed to open processed file stream.');
        }

        Storage::disk('public')->writeStream($relativePath, $stream, ['visibility' => 'public']);

        if (is_resource($stream)) {
            fclose($stream);
        }

        @unlink($processedPath);
        if ($processedPath !== $tempPath) {
            @unlink($tempPath);
        }

        return $relativePath;
    }
}
