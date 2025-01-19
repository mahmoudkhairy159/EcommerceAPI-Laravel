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
