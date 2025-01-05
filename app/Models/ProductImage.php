<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory, UploadFileTrait;
    protected $fillable = [
        'product_id',
        'image',
        'serial'
    ];
    //image
    const FILES_DIRECTORY = 'product_images';
    public $timestamps = false;

    protected $appends = ['image_url'];
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }
    //image

    //relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //relationships

}
