<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model implements TranslatableContract
{
    use HasFactory, Translatable, UploadFileTrait;
    public $translatedAttributes = ['title', 'slogan', 'summary', 'address'];

    const FILES_DIRECTORY = '/app';
    protected $guarded = [];

    protected $appends = ['logo_url', 'logo_light_url'];
    protected function getLogoUrlAttribute()
    {
        return $this->getFileAttribute($this->logo);
    }
    protected function getLogoLightUrlAttribute()
    {
        return $this->getFileAttribute($this->logo_light);
    }

    protected function emails(): Attribute
    {
        return Attribute::make(
            get: fn($value) => explode(',', $value),
        );
    }

    protected function phoneNumbers(): Attribute
    {
        return Attribute::make(
            get: fn($value) => explode(',', $value),
        );
    }
}
