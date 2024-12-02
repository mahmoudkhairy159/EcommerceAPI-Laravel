<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAccessory\DeleteProductAccessoriesRequest;
use App\Http\Requests\Admin\ProductAccessory\StoreProductAccessoryRequest;
use App\Http\Requests\Admin\ProductAccessory\UpdateProductAccessoriesRequest;
use App\Http\Resources\Admin\Product\ProductCollection;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ProductAccessoryController extends Controller
{
    use ApiResponseTrait;
    protected $productRepository;
    protected $_config;
    protected $guard;
    public function __construct(ProductRepository $productRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->productRepository = $productRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,product_accessories-read'])->only(['getProductAccessory']);
        $this->middleware(['ability:admin,product_accessories-create'])->only(['store']);
        $this->middleware(['ability:admin,product_accessories-update'])->only(['update']);
        $this->middleware(['ability:admin,product_accessories-delete'])->only(['destroy']);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function getProductAccessory($productId)
    {
        try {
            $product = $this->productRepository->findOrFail($productId);
            $data = $this->productRepository->getPaginatedProductAccessories($product)->paginate();
            return $this->successResponse(new ProductCollection($data));
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
    public function store(StoreProductAccessoryRequest $request)
    {
        try {
            $data = $request->validated();
            $created = $this->productRepository->addProductAccessories($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.productAccessories.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.productAccessories.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            //    return  $this->messageResponse( $e->getMessage());
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
    public function update(UpdateProductAccessoriesRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $updated = $this->productRepository->syncProductAccessories($$data);
            if ($updated) {
                return $this->messageResponse(
                    __("app.productAccessories.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.productAccessories.updated-failed"),
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
    public function destroy(DeleteProductAccessoriesRequest $request)
    {
        try {
            $data = $request->validated();
            $deleted = $this->productRepository->removeProductAccessories($$data);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.productAccessories.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.productAccessories.deleted-failed"),
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
