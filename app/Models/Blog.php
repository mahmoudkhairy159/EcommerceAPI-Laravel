<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, Sluggable,SoftDeletes,UploadFileTrait, Filterable;
    protected $fillable = [
        'title',
        'slug',
        'seo_description',
        'seo_keys',
        'body',
        'image',
        'status',
        'created_by',
        'updated_by',
    ];
    //slug
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['title', 'id'],
                'separator' => '-',
            ],
        ];
    }
    //slug

    //image
    const FILES_DIRECTORY = 'blogs';

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

 /**
     * Many-to-Many Relationship: Categories associated with the blog.
     */
    public function blogCategories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category', 'blog_id', 'category_id');
    }

    /**
     * One-to-Many Relationship: Comments on the blog.
     */
    public function blogComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }
}
