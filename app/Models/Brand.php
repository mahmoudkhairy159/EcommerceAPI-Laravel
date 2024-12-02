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
        'category_id',
        'image1', // Added image1
        'image2', // Added image2
        'short_description', // Added short_description
        'long_description', // Added long_description
        'long_description_status', // Added long_description
        'brief',
        'created_by',
        'updated_by',
        'status',
        'rank',
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

    protected $appends = ['image_1_url', 'image_2_url'];
    protected function getImage1UrlAttribute()
    {
        return $this->image1 ? $this->getFileAttribute($this->image1) : null;
    }
    protected function getImage2UrlAttribute()
    {
        return $this->image2 ? $this->getFileAttribute($this->image2) : null;
    }
//image
    //status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    //status
    public function modelFilter()
    {
        return $this->provideFilter(BrandFilter::class);
    }

    protected $table = 'brands';

    /*******************Relationships********************* */
    /**
     * Get the category associated with the brand.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
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
