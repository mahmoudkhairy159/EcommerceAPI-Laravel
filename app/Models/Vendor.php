<?php

namespace App\Models;

use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Vendor extends Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens,  UploadFileTrait, Filterable,  Sluggable;


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
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
    protected $guarded = [];
    //images
    const FILES_DIRECTORY = '/vendors';
    protected $appends = ['image_url'];
    //status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    //status
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }
    //

    //Mutators
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
    //Mutators
/**
 * The attributes that should be hidden for serialization.
 *
 * @var array<int, string>
 */
    protected $hidden = [
        'password',
        'remember_token',
    ];

/**
 * The attributes that should be cast.
 *
 * @var array<string, string>
 */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
