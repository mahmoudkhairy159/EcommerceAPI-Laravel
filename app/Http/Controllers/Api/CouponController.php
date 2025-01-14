<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Coupon\ApplyCouponRequest;
use App\Http\Resources\Api\Coupon\CouponCollection;
use App\Http\Resources\Api\Coupon\CouponResource;
use App\Repositories\CartRepository;
use App\Repositories\CouponRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    use ApiResponseTrait;
    protected $couponRepository;
    protected $cartRepository;
    protected $_config;
    protected $guard;
    public function __construct(CouponRepository $couponRepository,CartRepository $cartRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->couponRepository = $couponRepository;
        $this->cartRepository = $cartRepository;
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

            $data = $this->couponRepository->getAllActive()->paginate();
            return $this->successResponse(new CouponCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }



    public function apply(ApplyCouponRequest $request)
    {
        try {
            $data = $request->validated();
            $coupon = $this->couponRepository->getOneActiveByCode($data['code']);

            if (!$coupon) {
                return $this->messageResponse(
                    __("app.coupons.invalid-code"),
                    false,
                    404
                );
            }

            if ($coupon->quantity <=$coupon->total_used ) {
                return $this->messageResponse(
                    __("app.coupons.no-longer-available"),
                    false,
                    404
                );
            }
            $userId = Auth::id();
            $cartSubtotal = $this->cartRepository->getCartSumSubTotal($userId);
            $discountAmount = $this->couponRepository->calculateCouponDiscountAmount($coupon, $cartSubtotal);
            $data =[
                'coupon'=>new CouponResource($coupon),
                'coupon_discount_amount' => round($discountAmount),
                'main_cart_total' => round($cartSubtotal - $discountAmount),
            ];

            return $this->successResponse(
                $data,
                __("app.coupons.applied-successfully")
            );

        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
