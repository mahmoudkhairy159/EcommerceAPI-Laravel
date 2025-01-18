<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Order\ChangeOrderStatusRequest;
use App\Http\Resources\Vendor\Order\OrderCollection;
use App\Http\Resources\Vendor\Order\OrderResource;
use App\Repositories\OrderRepository;
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
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->orderRepository = $orderRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vendorId=auth()->id();
            $data = $this->orderRepository->getAllByVendorId($vendorId)->paginate();
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
            $vendorId=auth()->id();
            $data = $this->orderRepository->getByUserIdForVendor($user_id,$vendorId)->paginate();
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
            $vendorId=auth()->id();

            $data = $this->orderRepository->withWhereHas('products', function ($query) use ($vendorId) {
                $query->where('order_products.vendor_id', $vendorId);
            })->with(['user'])->findOrFail($id);
            return $this->successResponse(new OrderResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function getAllByStatus($status,)
    {
        try {
            $vendorId=auth()->id();
            $data = $this->orderRepository->getAllByStatusByVendorId($status,$vendorId)->paginate();
            return $this->successResponse(new OrderCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
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
