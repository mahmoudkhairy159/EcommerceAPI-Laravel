<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Product\UpdateProductTypeRequest;
use App\Http\Requests\Vendor\Product\StoreProductRequest;
use App\Http\Requests\Vendor\Product\UpdateProductRequest;
use App\Http\Requests\Vendor\Serial\UpdateSerialRequest;
use App\Http\Resources\Vendor\Product\ProductCollection;
use App\Http\Resources\Vendor\Product\ProductResource;
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
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
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
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }

    }




    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();
            $data['vendor_id'] = auth()->guard($this->guard)->id();
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
            $data['vendor_id'] = auth()->guard($this->guard)->id();
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

            $data['vendor_id'] = auth()->guard($this->guard)->id();
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
 public function updateProductType(UpdateProductTypeRequest $request,$id)
    {

        try {
            $data=$request->validated();
            $data['vendor_id'] = auth()->guard($this->guard)->id();
            $updated = $this->productRepository->updateProductType($id, $data);
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
            $data['vendor_id'] = auth()->guard($this->guard)->id();
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
       /***********Trashed model SoftDeletes**************/
       public function getOnlyTrashed()
       {
           try {
               $data = $this->productRepository->getOnlyTrashed()->paginate();
               return $this->successResponse(new ProductCollection($data));
           } catch (Exception $e) {
               return $this->errorResponse(
                   [],
                   __('app.something-went-wrong'),
                   500
               );
           }
       }

       public function forceDelete($id)
       {
           try {
               $deleted = $this->productRepository->forceDelete($id);
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

       public function restore($id)
       {
           try {
               $restored = $this->productRepository->restore($id);
               if ($restored) {
                   return $this->messageResponse(
                       __("app.products.restored-successfully"),
                       true,
                       200
                   );
               }{
                   return $this->messageResponse(
                       __("app.products.restored-failed"),
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
       /***********Trashed model SoftDeletes**************/
}
