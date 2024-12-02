<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Requests\Api\Order\UpdateOrderRequest;
use App\Http\Resources\Api\Order\OrderCollection;
use App\Http\Resources\Api\Order\OrderResource;
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
    protected $per_page;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->orderRepository = $orderRepository;
        $this->per_page = config('pagination.default');
        // permissions
        $this->middleware('auth:' . $this->guard);
    }

    public function index()
    {
        try {
            $user_id = auth()->guard($this->guard)->id();
            $ownedOrders = $this->orderRepository->getByUserId($user_id)->paginate($this->per_page);
            return $this->successResponse(new OrderCollection($ownedOrders));
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
    public function store(StoreOrderRequest $request, PurchaseOrderService $purchaseOrderService)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->guard($this->guard)->id();
            $created = $purchaseOrderService->purchaseOrder($data);
            if ($created) {
                return $this->successResponse(
                    new OrderResource($created),
                    __('app.orders.created-successfully'),
                    201
                );
            }{
                return $this->messageResponse(
                    __('app.orders.created-failed'),
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

            $data = $this->orderRepository->with('products')->findOrFail($id);
            if (!$data) {
                return $this->messageResponse(
                    __('app.data-not-found'),
                    false,
                    404
                );
            }
            if ($data->user_id != auth()->guard($this->guard)->id()) {
                return $this->errorResponse(
                    [],
                    __('app.unauthorized'),
                    403
                );
            }

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
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $id, UpdateOrderService $updateOrderService)
    {
        try {
            $order = $this->orderRepository->getOneById($id);
            if (!$order) {
                return $this->messageResponse(
                    __('app.data-not-found'),
                    false,
                    404
                );
            }
            if ($order->user_id != auth()->guard($this->guard)->id()) {
                return $this->errorResponse(
                    [],
                    __('app.unauthorized'),
                    403
                );
            }

            $data = $request->validated();
            $updated = $updateOrderService->updateOrder($data, $order);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $deleted = $this->orderRepository->deleteOneByUser($id);

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

}
