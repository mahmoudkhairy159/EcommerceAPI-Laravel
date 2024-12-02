<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadFileUsingMediaLibraryTrait
{
    public function uploadFilesWithMediaLibrary($model,$file_key,$collection_name='images' ,string $disk = "s3")
    {

        $fileExtension = request()->file($file_key)->getClientOriginalExtension();
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $collection_name = in_array(strtolower($fileExtension), $imageExtensions)?'images':'attachments_files';
        $model->addMediaFromRequest($file_key)->toMediaCollection($collection_name,$disk);
    }


    public function getFileWithMediaLibraryAttribute($filePath, $expirationDays = 6, $disk = 's3')
    {
        $temporaryUrl = Storage::disk($disk)->temporaryUrl(
            $filePath,
            now()->addDays($expirationDays)
        );

        return $temporaryUrl;
    }

}
