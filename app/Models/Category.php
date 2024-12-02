<?php

namespace App\Models;

use App\ModelFilters\CategoryFilter;
use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, Filterable, UploadFileTrait, SoftDeletes, Sluggable;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'code',
        'created_by',
        'updated_by',
        'status',
        'rank',

    ];
    //image
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
    const FILES_DIRECTORY = 'categories';

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
    public function modelFilter()
    {
        return $this->provideFilter(CategoryFilter::class);
    }

    protected $table = 'categories';

    /*******************Relationships********************* */

    /**
     * Get the brands for the category.
     */
    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    /*******************End Relationships********************* */

}
