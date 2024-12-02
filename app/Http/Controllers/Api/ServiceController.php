<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Service\ServiceCollection;
use App\Http\Resources\Api\Service\ServiceResource;
use App\Repositories\ServiceRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    use ApiResponseTrait;
    protected $serviceRepository;
    protected $_config;
    protected $guard;
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->serviceRepository = $serviceRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard)->except(['getFeaturedServices']);

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
            $data = $this->serviceRepository->getAll()->paginate();
            return $this->successResponse(new ServiceCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getWithoutPagination()
    {
        try {
            $data = $this->serviceRepository->getAll()->get();
            return $this->successResponse(ServiceResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getFeaturedServices()
    {
        try {
            $data = $this->serviceRepository->getFeaturedServices()->get();
            return $this->successResponse(ServiceResource::collection($data));
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
            $data = $this->serviceRepository->findOrFail($id);
            return $this->successResponse(new ServiceResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function showBySlug(string $slug)
    {
        try {
            $data = $this->serviceRepository->findActiveBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new ServiceResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
