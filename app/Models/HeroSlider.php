<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    use HasFactory, UploadFileTrait;
    protected $guarded = [];
     //status
     const STATUS_INACTIVE = 0;
     const STATUS_ACTIVE = 1;
     //status
    //images
    const FILES_DIRECTORY = '/hero-sliders';
    protected $appends = ['image_url'];
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }

}
