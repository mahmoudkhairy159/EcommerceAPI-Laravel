<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductVariant\ProductVariantCollection;
use App\Http\Resources\Api\ProductVariant\ProductVariantResource;
use App\Repositories\ProductVariantRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductVariantController extends Controller
{
    use ApiResponseTrait;
    protected $productVariantRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductVariantRepository $productVariantRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productVariantRepository = $productVariantRepository;
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
            $data = $this->productVariantRepository->getActiveByProductId($product_id)->paginate();
            return $this->successResponse(new ProductVariantCollection($data));
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
            $data = $this->productVariantRepository->getActiveOneById($id);
            return $this->successResponse(new ProductVariantResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
