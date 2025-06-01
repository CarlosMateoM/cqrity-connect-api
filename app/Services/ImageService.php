<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function uploadImage($image)
    {
        /** @var Filesystem $disk */
        
        $disk = Storage::disk('azure');

        $path = $disk->put('/', $image);

        return $disk->url($path);
    }
}
