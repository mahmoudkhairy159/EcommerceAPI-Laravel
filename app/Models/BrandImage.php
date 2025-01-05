<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    use HasFactory, UploadFileTrait;
    protected $fillable = [
        'brand_id',
        'image',
        'serial',
    ];
    //image
    const FILES_DIRECTORY = 'brand_images';
    public $timestamps = false;

    protected $appends = ['image_url'];
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }
    //image

    //relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //relationships

}
