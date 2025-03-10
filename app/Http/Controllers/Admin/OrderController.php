<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\ChangeOrderStatusRequest;
use App\Http\Requests\Admin\Order\StoreOrderRequest;
use App\Http\Requests\Admin\Order\UpdateOrderRequest;
use App\Http\Resources\Admin\Order\OrderCollection;
use App\Http\Resources\Admin\Order\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\PurchaseOrderService;
use App\Services\UpdateOrderService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiResponseTrait;

    protected $orderRepository;

    protected $_config;
    protected $guard;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->orderRepository = $orderRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,orders-read'])->only(['index', 'getByUserId', 'show']);
        $this->middleware(['ability:admin,orders-update'])->only(['changeStatus']);
        $this->middleware(['ability:admin,orders-delete'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->orderRepository->getAll()->paginate();
            return $this->successResponse(new OrderCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getAllByStatus($status)
    {
        try {
            $data = $this->orderRepository->getAllByStatus($status)->paginate();
            return $this->successResponse(new OrderCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function getByUserId($user_id)
    {
        try {
            $data = $this->orderRepository->getByUserId($user_id)->paginate();
            return $this->successResponse(new OrderCollection($data));
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
            $data = $this->orderRepository->with(['user','products'])->findOrFail($id);
            return $this->successResponse(new OrderResource($data));
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

            $deleted = $this->orderRepository->deleteOne($id);

            if ($deleted) {
                return $this->messageResponse(
                    __('app.orders.deleted-successfully'),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __('app.orders.deleted-failed'),
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
    // Change the status of an order
    public function changeStatus(ChangeOrderStatusRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $updated = $this->orderRepository->changeStatus($data, $id);

            if ($updated) {
                return $this->successResponse(
                    new OrderResource($updated),
                    __('app.orders.updated-successfully'),
                    200
                );
            }{
                return $this->messageResponse(
                    __('app.orders.updated-failed'),
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
