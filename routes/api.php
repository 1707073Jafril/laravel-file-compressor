<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('upload-image', [ImageController::class,'processImage']);
Route::post('upload-video', [ImageController::class,'processVideo']);
