<?php

namespace App\Models;

use App\ModelFilters\ReferenceCustomerFilter;
use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceCustomer extends Model
{
    use HasFactory, Filterable, UploadFileTrait, Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reference_customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'slug',
        'created_by',
        'updated_by',
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
    const FILES_DIRECTORY = '/reference-customers';

    protected $appends = ['image_url'];
    //image
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }
    //image
    public function modelFilter()
    {
        return $this->provideFilter(ReferenceCustomerFilter::class);
    }

}
