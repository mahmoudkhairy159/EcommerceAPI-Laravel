<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Advertisement\AdvertisementCollection;
use App\Http\Resources\Api\Advertisement\AdvertisementResource;
use App\Repositories\AdvertisementRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    use ApiResponseTrait;
    protected $advertisementRepository;
    protected $_config;
    protected $guard;
    public function __construct(AdvertisementRepository $advertisementRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->advertisementRepository = $advertisementRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);

    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->advertisementRepository->getAllActive()->paginate();
            return $this->successResponse(new AdvertisementCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByPosition($position)
    {
        try {
            $data = $this->advertisementRepository->getAllActiveByPosition($position)->get();
            return $this->successResponse( AdvertisementResource::Collection($data));
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
            $data = $this->advertisementRepository->findOrFail($id);
            return $this->successResponse(new AdvertisementResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function trackClick($id)
    {

        try {

            $updated = $this->advertisementRepository->trackClick($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.advertisements.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.advertisements.updated-failed"),
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


}
