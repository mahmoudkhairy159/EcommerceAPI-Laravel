<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\HeroSlider\HeroSliderCollection;
use App\Http\Resources\Api\HeroSlider\HeroSliderResource;
use App\Models\HeroSlider;
use App\Repositories\HeroSliderRepository;
use App\Traits\ApiResponseTrait;
use App\Traits\GeneralTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class HeroSliderController extends Controller
{

    use ApiResponseTrait;
    protected $heroSliderRepository;
    protected $_config;
    protected $guard;
    public function __construct(HeroSliderRepository $heroSliderRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->heroSliderRepository = $heroSliderRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }

    public function index()
    {
        try {
            $data = $this->heroSliderRepository->getAllActive()->get();
            return $this->successResponse(HeroSliderResource::collection($data));
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
            $data = $this->heroSliderRepository->getOneActiveById($id);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new HeroSliderResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
