<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductReview\StoreProductReviewRequest;
use App\Http\Requests\Api\ProductReview\UpdateProductReviewRequest;
use App\Http\Resources\Api\ProductReview\ProductReviewResource;
use App\Http\Resources\Api\ProductReview\ProductReviewCollection;
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
    protected $per_page;

    public function __construct(ProductReviewRepository $productReviewRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productReviewRepository = $productReviewRepository;
        // permissions
        $this->middleware('auth:' . $this->guard)->except([
            'getByProductId',
            'getByUserId',
            'show',
        ]);
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
    public function getByVendorId($serviceId)
    {
        try {
            $data = $this->productReviewRepository->getByVendorId($serviceId)->paginate();
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
     * Store a newly created resource in storage.
     */
    public function store(StoreProductReviewRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->guard($this->guard)->id();
            $created = $this->productReviewRepository->create($data);
            if ($created) {
                return $this->successResponse(
                    new ProductReviewResource($created),
                    __("app.productReviews.created-successfully"),
                    201
                );

            }{
                return $this->messageResponse(
                    __("app.productReviews.created-failed"),
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
     * Update the specified resource in storage.
     */
    public function update(UpdateProductReviewRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->guard($this->guard)->id();
            $updated = $this->productReviewRepository->updateOne($data, $id);

            if ($updated) {
                return $this->messageResponse(
                    __("app.productReviews.updated-successfully"),
                    true,
                    200
                );

            }{
                return $this->messageResponse(
                    __("app.productReviews.updated-failed"),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $data['user_id'] = auth()->guard($this->guard)->id();
            $productReview = $this->productReviewRepository->where('user_id', $data['user_id'])->findOrFail($id);
            $deleted = $this->productReviewRepository->delete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.productReviews.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.productReviews.deleted-successfully"),
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
