<?php

namespace App\Http\Controllers\Admin;
use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Types\CacheKeysType;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CityRepository;
use App\resources\Admin\City\CityCollection;
use App\resources\Admin\City\CityResource;
use App\Http\Requests\Admin\City\StoreCityRequest;
use App\Http\Requests\Admin\City\UpdateCityRequest;

class CityController extends Controller
{
    use ApiResponseTrait;


    protected $stateRepository;

    protected $_config;
    protected $guard;

    public function __construct(CityRepository $stateRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->stateRepository = $stateRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,cities-read'])->only(['index', 'show', 'getByCountryId']);
        $this->middleware(['ability:admin,cities-create'])->only(['store']);
        $this->middleware(['ability:admin,cities-update'])->only(['update']);
        $this->middleware(['ability:admin,cities-delete'])->only(['destroy', 'forceDelete', 'restore', 'getOnlyTrashed']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->stateRepository->getAll()->paginate();
            if (!$data) {
                return $this->messageResponse(
                    __("app.data_not_found'"),
                    false,
                    404
                );
            }
            return $this->successResponse(new CityCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
                __('app.something-went-wrong'),
                500
            );
        }
    }


    public function getByStateId($state_id)
    {
        try {
            $data = $this->stateRepository->getCitiesByStateId($state_id)->get();
            return $this->successResponse(CityResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByCountryId($country_id)
    {
        try {
            $data = $this->stateRepository->getCitiesByCountryId($country_id)->paginate();
            return $this->successResponse(new CityCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request)
    {
        try {
            $data =  $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->stateRepository->create($data);

            if ($created) {
                $this->clearCitiesCache();
                $this->clearCitiesByCountyIdCache($created->country_id);
                return $this->messageResponse(
                    __("area::app.cities.created-successfully"),
                    true,
                    201
                );
            } {
                return $this->messageResponse(
                    __("area::app.cities.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->stateRepository->findOrFail($id);
            return $this->successResponse(new CityResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, $id)
    {
        try {
            $state = $this->stateRepository->findOrFail($id);
            $data =  $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->stateRepository->update($data, $id);

            if ($updated) {
                $this->clearCitiesCache();
                $this->clearCitiesByCountyIdCache($state->country_id);
                return $this->messageResponse(
                    __("area::app.cities.updated-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("area::app.cities.updated-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $state=$this->stateRepository->findOrFail($id);
            $deleted = $this->stateRepository->delete($id);
            if ($deleted) {
                $this->clearCitiesCache();
                $this->clearCitiesByCountyIdCache($state->country_id);
                return $this->messageResponse(
                    __("area::app.cities.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("area::app.cities.deleted-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /***********Trashed model SoftDeletes**************/
    public function getOnlyTrashed()
    {
        try {
            $data = $this->stateRepository->getOnlyTrashed()->paginate();
            return $this->successResponse(new CityCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function forceDelete($id)
    {
        try {
            $deleted = $this->stateRepository->forceDelete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("area::app.cities.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("area::app.cities.deleted-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function restore($id)
    {
        try {
            $restored = $this->stateRepository->restore($id);
            if ($restored) {
                $this->clearCitiesCache();
                $this->clearCitiesByCountyIdCache($restored->country_id);
                return $this->messageResponse(
                    __("area::app.cities.restored-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("area::app.cities.restored-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    /***********Trashed model SoftDeletes**************/
    private function clearCitiesCache()
    {
        $this->deleteCache(CacheKeysType::CITIES_CACHE);
    }
    private function clearCitiesByCountyIdCache($country_id)
    {
        $this->deleteCache(CacheKeysType::citiesCacheKey($country_id));
    }
}
