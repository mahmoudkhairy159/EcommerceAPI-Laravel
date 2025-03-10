<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\StateRepository;
use App\Http\Resources\Api\State\StateResource;

class StateController extends Controller
{
    use ApiResponseTrait;


    protected $stateRepository;

    protected $_config;
    protected $guard;

    public function __construct(StateRepository $stateRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->stateRepository = $stateRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }


    public function getByCountryId($country_id)
    {
        try {
            $data = $this->stateRepository->getCachedActiveStatesByCountryId($country_id);
            return $this->successResponse(StateResource::collection($data));
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
            $data = $this->stateRepository->findOrFail($id);
            return $this->successResponse(new StateResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
