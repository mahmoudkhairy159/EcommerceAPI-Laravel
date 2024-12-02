<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, Sluggable;
    protected $fillable = ['slug','name', 'content'];
    public $timestamps = false;
    protected $casts = [
        'content' => 'array',
    ];
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
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

}
