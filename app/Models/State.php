<?php

namespace App\Models;

use App\Traits\ConnectionTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;


class State extends Model implements TranslatableContract
{
    use HasFactory, Filterable, Translatable, SoftDeletes;

    protected $table = 'states';


    /**
     * The attributes that can be translated.
     *
     * @var array
     */
    public $translatedAttributes = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'longitude',
        'latitude',
        'status',
        'created_by',
        'updated_by',
        'country_id',
    ];

    // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /************************************* Query Scopes ***************************************************/

    /**
     * Scope a query to only include active states.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /************************************* End Query Scopes ***********************************************/



    /********************************** Relationships *****************************************************/

    /**
     * Get the cities associated with the state.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get the country associated with the state.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Get the admin who created the state.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Get the admin who updated the state.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }


    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class, 'state_id');
    }


    /********************************** End Relationships *************************************************/

}
