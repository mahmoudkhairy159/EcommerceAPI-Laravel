<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BrandImage\BrandImageCollection;
use App\Http\Resources\Api\BrandImage\BrandImageResource;
use App\Repositories\BrandImageRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class BrandImageController extends Controller
{
    use ApiResponseTrait;
    protected $brandImageRepository;
    protected $_config;
    protected $guard;
    public function __construct(BrandImageRepository $brandImageRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->brandImageRepository = $brandImageRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function getByBrandId($product_id)
    {
        try {
            $data = $this->brandImageRepository->getByBrandId($product_id)->paginate();
            return $this->successResponse(new BrandImageCollection($data));
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
            $data = $this->brandImageRepository->findOrFail($id);
            return $this->successResponse(new BrandImageResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
