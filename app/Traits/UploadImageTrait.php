<?php

namespace App\Traits;

use Intervention\Image\Laravel\Facades\Image;

trait UploadImageTrait
{
    public function uploadImage($image, string $folder)
    {
        $imageName = $image->hashName();
        $path = '/uploads' . '/' . $folder . '/' . $imageName;
        $image = Image::make($image)->save(public_path($path), 100);
        return $imageName;
    }
    public function uploadImageAndResize($image, string $folder, int $width = 400, int $height = null)
    {
        $imageName = $image->hashName();
        $path = '/uploads' . '/' . $folder . '/' . $imageName;
        Image::make($image)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path($path), 100);
        return $imageName;
    }
}
