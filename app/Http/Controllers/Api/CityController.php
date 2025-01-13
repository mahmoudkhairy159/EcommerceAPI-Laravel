<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CityRepository;
use App\resources\Api\City\CityCollection;
use App\resources\Api\City\CityResource;

class CityController extends Controller
{
    use ApiResponseTrait;


    protected $cityRepository;

    protected $_config;
    protected $guard;

    public function __construct(CityRepository $cityRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->cityRepository = $cityRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }


    public function getByCountryId($country_id)
    {
        try {
            // $data = $this->cityRepository->getCachedActiveCitiesByCountryId($country_id);
            // return $this->successResponse(CityResource::collection(resource: $data));
            // dd(app()->getLocale());

            $data = $this->cityRepository->getActiveCitiesByCountryId($country_id)->paginate();
            return $this->successResponse(new CityCollection($data));

        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                $e->getMessage(),
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
            $data = $this->cityRepository->findOrFail($id);
            return $this->successResponse(new CityResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
