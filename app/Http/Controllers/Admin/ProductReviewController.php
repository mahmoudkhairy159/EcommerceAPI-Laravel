<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductReview\ProductReviewCollection;
use App\Http\Resources\Admin\ProductReview\ProductReviewResource;
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
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productReviewRepository = $productReviewRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,product_reviews-read'])->only(['index', 'show', 'getByItemId', 'getByUserId']);
        $this->middleware(['ability:admin,product_reviews-delete'])->only(['destroy', 'forceDelete', 'restore', 'getOnlyTrashed']);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $productReview = $this->productReviewRepository->findOrFail($id);
            $deleted = $this->productReviewRepository->delete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.productReviews.deleted-successfully"),
                    true,
                200
                );
            }{
                return $this->messageResponse(
                    __("app.productReviews.deleted-failed"),
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
