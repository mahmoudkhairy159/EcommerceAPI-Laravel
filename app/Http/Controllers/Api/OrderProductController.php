<?php

namespace App\Http\Controllers\Api;

use App\Http\Api\OrderProduct\OrderProductCollection;
use App\Http\Api\OrderProduct\OrderProductResource;
use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\OrderProduct\UpdateOrderProductRequest;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;


class OrderProductController extends Controller
{
    use ApiResponseTrait;


    protected $orderProductRepository;
    protected $orderRepository;

    protected $_config;
    protected $guard;

    public function __construct(OrderProductRepository $orderProductRepository,OrderRepository $orderRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->orderProductRepository = $orderProductRepository;
        $this->orderRepository = $orderRepository;

        // permissions
        $this->middleware('auth:' . $this->guard);
    }
    /**
     * Display a listing of the resource.
     */
    public function getByOrderId($orderId)
    {
        try {
            $data = $this->orderRepository->getOneById($orderId);
            if (!$data) {
                return $this->messageResponse(
                    __('app.data-not-found'),
                    false,
                    404
                );
            }
            if($data->user_id != auth()->guard($this->guard)->id()){
                return $this->errorResponse(
                    [],
                    __('app.unauthorized'),
                    403
                );
            }
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
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderProductRequest $request, $id)
    {
        try {
            $data = $this->orderProductRepository->where('user_id', auth()->guard($this->guard)->id())->findOrFail($id);
            $data =  $request->validated();
            $updated = $this->orderProductRepository->update($data, $id);

            if ($updated) {
                return $this->successResponse(
                    new OrderProductResource($updated),
                    __('app.orderProducts.updated-successfully'),
                    200
                );
            } {
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
            $data = $this->orderProductRepository->where('user_id', auth()->guard($this->guard)->id())->findOrFail($id);
            $deleted = $this->orderProductRepository->delete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __('app.orderProducts.deleted-successfully'),
                    true,
                    200
                );
            } {
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










