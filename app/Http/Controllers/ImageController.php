<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\MediaType;
use Intervention\Image\Facades\Image;
use FFMpeg;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;

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

            //Applying compressing using image file path
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($path);
            $encoded = $image->toJpeg(quality: 60);
            $imageName_encoded = time() . '.jpeg'; 
            $destinationPath = public_path('edited_img/' . $imageName_encoded);
            $encoded->save($destinationPath);

            return response()->json([
                'message' => 'Image uploaded & Compressed successfully!',
                'original_image_url' => $path, 
                'edited_image_url' => $destinationPath, 
            ], 200);
        } else {
            return response()->json([
                'error' => 'Failed to upload or decode the image. Please try again.',
            ], 400);
        }

    }
    
}

