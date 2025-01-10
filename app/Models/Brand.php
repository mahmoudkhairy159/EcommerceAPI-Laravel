<?php

namespace App\Models;

use App\ModelFilters\BrandFilter;
use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, Filterable, UploadFileTrait, SoftDeletes, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'image', // Added image
        'short_description', // Added short_description
        'long_description', // Added long_description
        'long_description_status', // Added long_description
        'brief',
        'created_by',
        'updated_by',
        'status',
        'is_featured',
        'serial',
    ];
    //slug
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'id'],
                'separator' => '-',
            ],
        ];
    }
    //slug
//image
    const FILES_DIRECTORY = 'brands';

    protected $appends = ['image_url'];
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }

//image
    //status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    //status
     //Show in home
     const IS_FEATURED_ACTIVE = 1;
     const IS_FEATURED_INACTIVE = 0;
     //Show in home
    public function modelFilter()
    {
        return $this->provideFilter(BrandFilter::class);
    }

    protected $table = 'brands';

    /*******************Relationships********************* */
    
    public function products()
    {

        return $this->hasMany(Product::class);
    }
    public function brandImages()
    {
        return $this->hasMany(BrandImage::class);
    }
    /*******************Relationships********************* */

}
