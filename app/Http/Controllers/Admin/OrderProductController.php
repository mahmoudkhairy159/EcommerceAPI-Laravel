<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderProduct\StoreOrderProductRequest;
use App\Http\Requests\Admin\OrderProduct\UpdateOrderProductRequest;
use App\Http\Resources\Admin\OrderProduct\OrderProductCollection;
use App\Http\Resources\Admin\OrderProduct\OrderProductResource;
use App\Repositories\OrderProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class OrderProductController extends Controller
{
    use ApiResponseTrait;

    protected $orderProductRepository;

    protected $_config;
    protected $guard;

    public function __construct(OrderProductRepository $orderProductRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->orderProductRepository = $orderProductRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,order_products-read'])->only(['getByOrderId', 'show']);
        $this->middleware(['ability:admin,order_products-create'])->only(['store']);
        $this->middleware(['ability:admin,order_products-update'])->only(['update']);
        $this->middleware(['ability:admin,order_products-delete'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function getByOrderId($orderId)
    {
        try {
            $data = $this->orderProductRepository->getByOrderId($orderId)->paginate();
            return $this->successResponse(new OrderProductCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderProductRequest $request)
    {
        try {
            $data = $request->validated();
            $created = $this->orderProductRepository->create($data);
            if ($created) {
                return $this->successResponse(
                    new OrderProductResource($created),
                    __('app.orderProducts.created-successfully'),
                    201
                );
            }{
                return $this->messageResponse(
                    __('app.orderProducts.created-failed'),
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
            $data = $this->orderProductRepository->findOrFail($id);
            return $this->successResponse(new OrderProductResource($data));
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
    public function update(UpdateOrderProductRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $updated = $this->orderProductRepository->update($data, $id);

            if ($updated) {
                return $this->successResponse(
                    new OrderProductResource($updated),
                    __('app.orderProducts.updated-successfully'),
                    200
                );
            }{
                return $this->messageResponse(
                    __('app.orderProducts.updated-failed'),
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

            $deleted = $this->orderProductRepository->delete($id);

            if ($deleted) {
                return $this->messageResponse(
                    __('app.orderProducts.deleted-successfully'),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __('app.orderProducts.deleted-failed'),
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
