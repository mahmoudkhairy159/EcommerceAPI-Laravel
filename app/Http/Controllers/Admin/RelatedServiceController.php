<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RelatedService\DeleteRelatedServicesRequest;
use App\Http\Requests\Admin\RelatedService\StoreRelatedServicesRequest;
use App\Http\Requests\Admin\RelatedService\UpdateRelatedServicesRequest;
use App\Http\Resources\Admin\Service\ServiceCollection;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class RelatedServiceController extends Controller
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
        $this->middleware(['ability:admin,related_services-read'])->only(['getRelatedServices']);
        $this->middleware(['ability:admin,related_services-create'])->only(['store']);
        $this->middleware(['ability:admin,related_services-update'])->only(['update']);
        $this->middleware(['ability:admin,related_services-delete'])->only(['destroy']);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function getRelatedServices($productId)
    {
        try {
            $product = $this->productRepository->findOrFail($productId);
            $data = $this->productRepository->getPaginatedRelatedServices($product)->paginate();
            return $this->successResponse(new ServiceCollection($data));
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
    public function store(StoreRelatedServicesRequest $request)
    {
        try {
            $data = $request->validated();
            $created = $this->productRepository->addRelatedServices($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.relatedServices.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.relatedServices.created-failed"),
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
    public function update(UpdateRelatedServicesRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $updated = $this->productRepository->syncRelatedServices($$data);
            if ($updated) {
                return $this->messageResponse(
                    __("app.relatedServices.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.relatedServices.updated-failed"),
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
    public function destroy(DeleteRelatedServicesRequest $request)
    {
        try {
            $data = $request->validated();
            $deleted = $this->productRepository->removeRelatedServices($$data);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.relatedServices.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.relatedServices.deleted-failed"),
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
