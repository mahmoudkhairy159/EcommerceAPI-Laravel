<?php

namespace App\Http\Controllers\Vendor;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\ProductVariant\StoreProductVariantRequest;
use App\Http\Requests\Vendor\ProductVariant\UpdateProductVariantRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ProductVariantRepository;
use App\Http\Resources\Vendor\ProductVariant\ProductVariantCollection;
use App\Http\Resources\Vendor\ProductVariant\ProductVariantResource;
use App\Repositories\ProductRepository;

class ProductVariantController extends Controller
{
    use ApiResponseTrait;
    protected $productVariantRepository;
    protected $productRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductVariantRepository $productVariantRepository,ProductRepository $productRepository)
    {
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productVariantRepository = $productVariantRepository;
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
            $data = $this->productVariantRepository->getByProductId($product_id)->paginate();
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
     * Store a newly created resource in storage.
     */
    public function store(StoreProductVariantRequest $request)
    {
        try {
            $data =  $request->validated();
            $created = $this->productVariantRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.productVariants.created-successfully"),
                    true,
                    201
                );
            } {
                return $this->messageResponse(
                    __("app.productVariants.created-failed"),
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
            $data = $this->productVariantRepository->findOrFail($id);
            return $this->successResponse(new ProductVariantResource($data));
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
    public function update(UpdateProductVariantRequest $request, $id)
    {
        try {

            $data =  $request->validated();
            $updated = $this->productVariantRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.productVariants.updated-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.productVariants.updated-failed"),
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
            $isOwner=$this->productRepository->checkProductOwnership($id,'productVariants');
            if (!$isOwner) {
                return $this->errorResponse(
                    [],
                    __("Unauthorized"),
                    403
                );
            }

            $deleted = $this->productVariantRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.productVariants.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.productVariants.deleted-failed"),
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
