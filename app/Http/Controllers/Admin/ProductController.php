<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Requests\Admin\Serial\UpdateSerialRequest;
use App\Http\Resources\Admin\Product\ProductCollection;
use App\Http\Resources\Admin\Product\ProductResource;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
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
        $this->middleware(['ability:admin,products-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,products.create'])->only(['store']);
        $this->middleware(['ability:admin,products.update'])->only(['update']);
        $this->middleware(['ability:admin,products-delete'])->only(['destroy']);
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
            $data = $this->productRepository->getAll()->paginate();
            return $this->successResponse(new ProductCollection($data));
        } catch (Exception $e) {
            dd($e->getMessage());
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function getStatistics()
    {
        try {
            $data = $this->productRepository->getStatistics();
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getStatisticsById($id)
    {
        try {
            $data = $this->productRepository->getStatisticsById($id);
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getLowQuantityAlertProductsCount()
    {
        try {
            $data = $this->productRepository->getLowQuantityAlertProductsCount();
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function getFavoriteCustomersCountByProductId($id)
    {

        try {
            $data = $this->productRepository->getFavoriteCustomersCountByProductId($id);
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }

    }
    public function getProductsBanner()
    {
        try {
            return file_get_contents("product_banners.txt");
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }

    }



    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->productRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.products.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.products.created-failed"),
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
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->productRepository->getOneById($id);
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
            $data = $this->productRepository->findBySlug($slug);
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->productRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.products.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.products.updated-failed"),
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
    public function changeStatus($id)
    {

        try {

            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->productRepository->changeStatus($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.products.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.products.updated-failed"),
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
    public function updateFeaturedStatus($id)
    {

        try {

            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->productRepository->updateFeaturedStatus($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.products.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.products.updated-failed"),
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
    public function updateSerial(UpdateSerialRequest $request, $id)
    {

        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->productRepository->updateSerial($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.products.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.products.updated-failed"),
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
            $deleted = $this->productRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.products.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.products.deleted-failed"),
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

    public function deleteImage($id)
    {
        try {
            $deleted = $this->productRepository->deleteImage($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.products.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.products.deleted-failed"),
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
