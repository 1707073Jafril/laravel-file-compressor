<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\MediaType;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    public function processImage(Request $request)
    {
        $uploadPath = public_path('uploaded_img');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true); 
        }
        if (!file_exists(public_path('edited_img'))) {
            mkdir(public_path('edited_img'), 0777, true);
        }
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move($uploadPath, $imageName);
            $path = public_path('uploaded_img/' . $imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($path);
            $encoded = $image->toJpeg(quality: 60);
            $imageName_encoded = time() . '.jpeg'; // You can change the name of the file if needed
            $destinationPath = public_path('edited_img/' . $imageName_encoded); // Path to save the image in public/edited folder
            $encoded->save($destinationPath);

            // For web response
            return response()->json([
                'message' => 'Image uploaded successfully!',
                'image_url' => $path, // URL to access the uploaded image
                'image_name' => $imageName, // Optionally return the image file name
            ], 200);
        } else {
            // Return error response if the image wasn't uploaded or is invalid
            return response()->json([
                'error' => 'Failed to upload the image. Please try again.',
            ], 400);
        }

    }


    public function processVideo(Request $request): JsonResponse 
    {
        
    }
    
}

