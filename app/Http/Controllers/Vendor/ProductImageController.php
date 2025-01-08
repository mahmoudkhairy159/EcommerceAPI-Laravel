<?php

namespace App\Http\Controllers\Vendor;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ProductImageRepository;
use App\Http\Requests\Vendor\ProductImage\StoreProductImageRequest;
use App\Http\Requests\Vendor\ProductImage\UpdateProductImageRequest;
use App\Http\Resources\Vendor\ProductImage\ProductImageCollection;
use App\Http\Resources\Vendor\ProductImage\ProductImageResource;
use App\Repositories\ProductRepository;

class ProductImageController extends Controller
{
    use ApiResponseTrait;
    protected $productImageRepository;
    protected $productRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductImageRepository $productImageRepository,ProductRepository $productRepository)
    {
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productImageRepository = $productImageRepository;
        $this->productRepository = $productRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware('checkVendorProductOwnership')->only([
            'getByProductId',
            'store',
            'update',
        ]);

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
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request)
    {
        try {
            $data =  $request->validated();
            $created = $this->productImageRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.productImages.created-successfully"),
                    true,
                    201
                );
            } {
                return $this->messageResponse(
                    __("app.productImages.created-failed"),
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


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductImageRequest $request, $id)
    {
        try {

            $data =  $request->validated();
            $updated = $this->productImageRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.productImages.updated-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.productImages.updated-failed"),
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
            $isOwner=$this->productRepository->checkProductOwnership($id,'productImages');
            if (!$isOwner) {
                return $this->errorResponse(
                    [],
                    __("Unauthorized"),
                    403
                );
            }
            $deleted = $this->productImageRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.productImages.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.productImages.deleted-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
