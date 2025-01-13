<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory,Filterable;
    protected $fillable = [
        'user_id',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'address',
        'created_by',
        'updated_by',
    ];

    /**
     * Define the relationship to the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship to the Country.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Define the relationship to the State.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Define the relationship to the City.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
