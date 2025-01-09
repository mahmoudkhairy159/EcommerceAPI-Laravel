<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FlashSaleProduct\StoreFlashSaleProductRequest;
use App\Http\Requests\Admin\FlashSaleProduct\UpdateFlashSaleProductRequest;
use App\Http\Resources\Admin\FlashSaleProduct\FlashSaleProductCollection;
use App\Http\Resources\Admin\FlashSaleProduct\FlashSaleProductResource;
use App\Repositories\FlashSaleProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class FlashSaleProductController extends Controller
{
    use ApiResponseTrait;
    protected $flashSaleProductRepository;
    protected $_config;
    protected $guard;
    public function __construct(FlashSaleProductRepository $flashSaleProductRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->flashSaleProductRepository = $flashSaleProductRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,flash_sale_Products-read'])->only(['index' ]);
        $this->middleware(['ability:admin,flash_sale_Products-create'])->only(['store']);
        $this->middleware(['ability:admin,flash_sale_Products-update'])->only(['update']);
        $this->middleware(['ability:admin,flash_sale_Products-delete'])->only(['destroy']);
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
            $data = $this->flashSaleProductRepository->getAll()->paginate();
            return $this->successResponse(new FlashSaleProductCollection($data));
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
    public function store(StoreFlashSaleProductRequest $request)
    {
        try {
            $data = $request->validated();
            $data['flash_sale_id'] = 1;
            $created = $this->flashSaleProductRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.flashSaleProducts.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.flashSaleProducts.created-failed"),
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
     * Update the specified resource in storage.
     */
    public function update(UpdateFlashSaleProductRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['flash_sale_id'] = 1;
            $updated = $this->flashSaleProductRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.flashSaleProducts.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.flashSaleProducts.updated-failed"),
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
            $deleted = $this->flashSaleProductRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.flashSaleProducts.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.flashSaleProducts.deleted-failed"),
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
