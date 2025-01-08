<?php

namespace App\Http\Controllers\Vendor;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ProductVariantItemRepository;
use App\Http\Requests\Vendor\ProductVariantItem\StoreProductVariantItemRequest;
use App\Http\Requests\Vendor\ProductVariantItem\UpdateProductVariantItemRequest;
use App\Http\Resources\Vendor\ProductVariantItem\ProductVariantItemCollection;
use App\Http\Resources\Vendor\ProductVariantItem\ProductVariantItemResource;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;

class ProductVariantItemController extends Controller
{
    use ApiResponseTrait;
    protected $productVariantItemRepository;
    protected $productRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductVariantItemRepository $productVariantItemRepository,ProductRepository $productRepository)
    {
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productVariantItemRepository = $productVariantItemRepository;
        $this->productRepository = $productRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
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
            $data = $this->productVariantItemRepository->getByProductVariantId($product_variant_id)->paginate();
            return $this->successResponse(new ProductVariantItemCollection($data));
        } catch (Exception $e) {
            dd($e->getMessage());

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
    public function store(StoreProductVariantItemRequest $request)
    {
        try {
            $isOwner=$this->productRepository->checkProductOwnership($request->product_variant_id,'productVariants');
            if (!$isOwner) {
                return $this->errorResponse(
                    [],
                    __("Unauthorized"),
                    403
                );
            }
            $data =  $request->validated();
            $created = $this->productVariantItemRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.productVariantItems.created-successfully"),
                    true,
                    201
                );
            } {
                return $this->messageResponse(
                    __("app.productVariantItems.created-failed"),
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
            $data = $this->productVariantItemRepository->findOrFail($id);
            return $this->successResponse(new ProductVariantItemResource($data));
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
    public function update(UpdateProductVariantItemRequest $request, $id)
    {
        try {
            $isOwner=$this->productVariantItemRepository->checkProductOwnership($id);
            if (!$isOwner) {
                return $this->errorResponse(
                    [],
                    __("Unauthorized"),
                    403
                );
            }
            $data =  $request->validated();
            $updated = $this->productVariantItemRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.productVariantItems.updated-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.productVariantItems.updated-failed"),
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
            $isOwner=$this->productVariantItemRepository->checkProductOwnership($id);
            if (!$isOwner) {
                return $this->errorResponse(
                    [],
                    __("Unauthorized"),
                    403
                );
            }
            $deleted = $this->productVariantItemRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.productVariantItems.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.productVariantItems.deleted-failed"),
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
