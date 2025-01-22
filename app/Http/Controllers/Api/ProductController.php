<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Product\ProductCollection;
use App\Http\Resources\Api\Product\ProductResource;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
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
        // $this->middleware('auth:' . $this->guard)->except('getFeaturedProducts');

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
            $data = $this->productRepository->getAllActive()->paginate();
            return $this->successResponse(new ProductCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByVendorId($vendorId)
    {
        try {
            $data = $this->productRepository->getAllActiveByVendorId($vendorId)->paginate();
            return $this->successResponse(new ProductCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getFeaturedProducts()
    {
        try {
            $data = $this->productRepository->getFeaturedProducts()->get();
            return $this->successResponse(ProductResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
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
            $data = $this->productRepository->getOneActiveById($id);
            return $this->successResponse(new ProductResource($data));
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
            $data = $this->productRepository->findActiveBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new ProductResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
