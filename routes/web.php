<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

//require __DIR__.'/auth.php';

Route::post('upload-image', [ImageController::class,'processImage']);