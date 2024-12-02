<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Banner\BannerCollection;
use App\Http\Resources\Api\Banner\BannerResource;
use App\Repositories\BannerRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{

    use ApiResponseTrait;
    protected $heroSliderRepository;
    protected $_config;
    protected $guard;
    public function __construct(BannerRepository $heroSliderRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->heroSliderRepository= $heroSliderRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }

    public function index()
    {
        try {
            $data = $this->heroSliderRepository->getAll()->get();
            return $this->successResponse( BannerResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function show($id)
    {
        try {
            $data = $this->heroSliderRepository->findOrFail($id);
            return $this->successResponse(new BannerResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
