<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProductVariantItem\ProductVariantItemCollection;
use App\Http\Resources\Api\ProductVariantItem\ProductVariantItemResource;
use App\Repositories\ProductVariantItemRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductVariantItemController extends Controller
{
    use ApiResponseTrait;
    protected $productVariantItemRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductVariantItemRepository $productVariantItemRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productVariantItemRepository = $productVariantItemRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function getByProductVariantId($product_variant_id)
    {
        try {
            $data = $this->productVariantItemRepository->getByActiveProductVariantId($product_variant_id)->paginate();
            return $this->successResponse(new ProductVariantItemCollection($data));
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
            $data = $this->productVariantItemRepository->getActiveOneById($id);
            return $this->successResponse(new ProductVariantItemResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
