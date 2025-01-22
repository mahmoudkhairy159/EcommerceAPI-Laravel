<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\ProductReview\ProductReviewCollection;
use App\Http\Resources\Vendor\ProductReview\ProductReviewResource;
use App\Repositories\ProductReviewRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    use ApiResponseTrait;

    protected $productReviewRepository;

    protected $_config;
    protected $guard;

    public function __construct(ProductReviewRepository $productReviewRepository)
    {
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productReviewRepository = $productReviewRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);

    }

    public function getByProductId($productId)
    {
        try {
            $data = $this->productReviewRepository->getByProductId($productId)->paginate();
            return $this->successResponse(new ProductReviewCollection($data));
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
            $data = $this->productReviewRepository->getByVendorId($vendorId)->paginate();
            return $this->successResponse(new ProductReviewCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByUserId($userId)
    {
        try {
            $data = $this->productReviewRepository->getByUserId($userId)->paginate();
            return $this->successResponse(new ProductReviewCollection($data));
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
            $data = $this->productReviewRepository->findOrFail($id);
            return $this->successResponse(new ProductReviewResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }



}
