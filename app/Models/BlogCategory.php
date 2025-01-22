<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory,SoftDeletes, Filterable, Sluggable;
    protected $fillable = ['name', 'slug','created_by', 'updated_by'];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'id'],
                'separator' => '-',
            ],
        ];
    }
    /**
     * Many-to-Many Relationship: Blogs associated with the category.
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category', 'category_id', 'blog_id');
    }
}
