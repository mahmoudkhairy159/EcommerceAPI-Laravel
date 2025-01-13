<?php

namespace App\Repositories;

use App\Traits\SoftDeletableTrait;
use App\Types\CacheKeysType;
use Illuminate\Support\Facades\Cache;
use App\Models\City;
use Prettus\Repository\Eloquent\BaseRepository;

class CityRepository extends BaseRepository
{
    use SoftDeletableTrait;

    public $retrievedData = [
        'id',
        'state_id',
        'country_id',
        'status',
    ];
    public function model()
    {
        return City::class;
    }
    public function getAll()
    {
        return $this->model
            ->select($this->retrievedData)
            ->filter(request()->all())
            ->orderBy('created_at', 'asc');
    }


    public function getCachedActiveCitiesByCountryId($country_id)
    {
        return Cache::remember(CacheKeysType::statesCacheKey($country_id), now()->addDays(5), function () use ($country_id) {
            return $this->getActiveCitiesByCountryId($country_id)->get();
        });
    }
    public function getCitiesByCountryId($country_id)
    {
        return $this->model
            ->select($this->retrievedData)
            ->where('country_id', $country_id)
            ->filter(request()->all())
            ->orderBy('created_at', 'asc');
    }
    public function getCitiesByStateId($state_id)
    {
        return $this->model
            ->select($this->retrievedData)
            ->where('state_id', $state_id)
            ->filter(request()->all())
            ->orderBy('created_at', 'asc');
    }
    public function getActiveCitiesByCountryId($country_id)
    {
        return $this->model
            ->select($this->retrievedData)
            ->where('country_id', $country_id)
            ->active()
            ->filter(request()->all())
            ->orderBy('created_at', 'asc');
    }
    public function getActiveCitiesByStateId($state_id)
    {
        return $this->model
            ->select($this->retrievedData)
            ->where('state_id', $state_id)
            ->active()
            ->filter(request()->all())
            ->orderBy('created_at', 'asc')->get();
    }



}
