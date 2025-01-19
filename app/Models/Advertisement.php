<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory,Filterable,UploadFileTrait;
    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'position',
        'clicks',
        'status',
        'created_by',
        'updated_by',
    ];
    const FILES_DIRECTORY = '/advertisements';
     //status
     const STATUS_INACTIVE = 0;
     const STATUS_ACTIVE = 1;
     //status
     protected $appends = ['image_url'];
     protected function getImageUrlAttribute()
     {
         return $this->image ? $this->getFileAttribute($this->image) : null;
     }
}
