<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Product\ProductResource;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class RelatedProductController extends Controller
{
    use ApiResponseTrait;
    protected $productRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductRepository $productRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productRepository = $productRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);

    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function getRelatedProducts($productId)
    {
        try {
            $product = $this->productRepository->findOrFail($productId);
            $data = $this->productRepository->getRelatedProducts($product, 4)->get();
            return $this->successResponse(ProductResource::Collection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
