<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductImage\ProductImageCollection;
use App\Http\Resources\Api\ProductImage\ProductImageResource;
use App\Repositories\ProductImageRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductImageController extends Controller
{
    use ApiResponseTrait;
    protected $productImageRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductImageRepository $productImageRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productImageRepository = $productImageRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function getByProductId($product_id)
    {
        try {
            $data = $this->productImageRepository->getByProductId($product_id)->paginate();
            return $this->successResponse(new ProductImageCollection($data));
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
            $data = $this->productImageRepository->findOrFail($id);
            return $this->successResponse(new ProductImageResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
