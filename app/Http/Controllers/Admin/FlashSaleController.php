<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FlashSale\StoreFlashSaleProductsRequest;
use App\Http\Requests\Admin\FlashSale\StoreFlashSaleRequest;
use App\Http\Requests\Admin\FlashSale\UpdateFlashSaleRequest;
use App\Http\Requests\Admin\FlashSaleProduct\StoreFlashSaleProductRequest;
use App\Http\Resources\Admin\FlashSale\FlashSaleCollection;
use App\Http\Resources\Admin\FlashSale\FlashSaleResource;
use App\Repositories\FlashSaleRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class FlashSaleController extends Controller
{
    use ApiResponseTrait;
    protected $flashSaleRepository;
    protected $_config;
    protected $guard;
    public function __construct(FlashSaleRepository $flashSaleRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->flashSaleRepository = $flashSaleRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,flash_sales-read'])->only(['index']);
        $this->middleware(['ability:admin,flash_sales-update'])->only(['update']);
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
            $data = $this->flashSaleRepository->getFlashSale();
            return $this->successResponse(new FlashSaleResource($data));
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
    public function update(UpdateFlashSaleRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->flashSaleRepository->updateOne($data);
            if ($updated) {
                return $this->messageResponse(
                    __("app.flashSales.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.flashSales.updated-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            // return  $this->messageResponse( $e->getMessage());

            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }





}
