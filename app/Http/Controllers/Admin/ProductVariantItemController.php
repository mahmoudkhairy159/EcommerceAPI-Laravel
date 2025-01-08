<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ProductVariantItemRepository;
use App\Http\Requests\Admin\ProductVariantItem\StoreProductVariantItemRequest;
use App\Http\Requests\Admin\ProductVariantItem\UpdateProductVariantItemRequest;
use App\Http\Resources\Admin\ProductVariantItem\ProductVariantItemCollection;
use App\Http\Resources\Admin\ProductVariantItem\ProductVariantItemResource;

class ProductVariantItemController extends Controller
{
    use ApiResponseTrait;
    protected $productVariantItemRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductVariantItemRepository $productVariantItemRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productVariantItemRepository = $productVariantItemRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,products-read'])->only(['getByProductId', 'show']);
        $this->middleware(['ability:admin,products-create'])->only(['store']);
        $this->middleware(['ability:admin,products-update'])->only(['update']);
        $this->middleware(['ability:admin,products-delete'])->only(['destroy']);
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
            $data =  $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
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

            $data =  $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
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
