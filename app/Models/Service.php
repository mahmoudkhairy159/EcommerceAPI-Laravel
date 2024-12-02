<?php

namespace App\Models;

use App\ModelFilters\ServiceFilter;
use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, Filterable, UploadFileTrait, SoftDeletes, Sluggable;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'main_category',
        'image',
        'code',
        'category_id',
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
    const FILES_DIRECTORY = 'services';

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
        return $this->provideFilter(ServiceFilter::class);
    }

    protected $table = 'services';
    //relationships
    public function Category()
    {

        return $this->belongsTo(Category::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_service');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}
