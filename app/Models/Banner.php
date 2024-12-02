<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, UploadFileTrait;
    protected $guarded = [];
    //images
    const FILES_DIRECTORY = '/banners';
    protected $appends = ['image_url'];
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }
    //images

}
