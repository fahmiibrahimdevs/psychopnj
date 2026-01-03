<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadImageSummernote(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png|max:4096',
        ]);

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();

        $directory = 'public/';
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
            case 'png':
                $directory .= 'image/';
                break;
            default:
                $directory .= 'misc/';
                break;
        }

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        $originalName = str_replace(' ', '_', pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $originalName . '_' . time() . '.' . $ext;

        try {
            $save = Storage::putFileAs($directory, $file, $filename);

            if ($save) {
                $relativePath = str_replace('public/', '', $directory);

                return response()->json([
                    "status" => "success",
                    "path" => $relativePath,
                    "image" => $filename,
                    "image_url" => Storage::url($directory . $filename)
                ]);
            } else {
                return response()->json([
                    "status" => "fail",
                    "message" => "The file failed to save."
                ], 500);
            }
        } catch (\Exception $e) {
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
