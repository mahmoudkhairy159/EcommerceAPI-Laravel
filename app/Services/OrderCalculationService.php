<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\CouponRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShippingRuleRepository;
use App\Repositories\UserAddressRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderCalculationService
{
    protected $cartRepository;
    protected $userAddressRepository;
    protected $couponRepository;
    protected $shippingRuleRepository;

    public function __construct(
        UserAddressRepository $userAddressRepository,
        CartRepository $cartRepository,
        CouponRepository $couponRepository,
        ShippingRuleRepository $shippingRuleRepository,
    ) {
        $this->cartRepository = $cartRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->couponRepository = $couponRepository;
        $this->shippingRuleRepository = $shippingRuleRepository;
    }
    public function calculateOrderAmount(array $data): array
    {
        $data['sub_total'] = $this->cartRepository->getCartSumSubTotal($data['user_id']);
        $data['order_address'] = $this->userAddressRepository->find($data['user_address_id']);
        $data['shipping_rule'] = $this->shippingRuleRepository->find($data['shipping_rule_id']);
        $data['coupon'] = isset($data['code']) ? $this->couponRepository->getOneActiveByCode($data['code']) : null;

        $data['discount_amount'] = !$data['coupon'] || $data['coupon']->quantity <= $data['coupon']->total_used
            ? 0
            : $this->couponRepository->calculateCouponDiscountAmount($data['coupon'], $data['sub_total']);

        $data['shipping_rule_amount'] = $this->shippingRuleRepository->calculateShippingRuleAmount($data['shipping_rule']);
        $data['amount'] = round($data['sub_total'] + $data['shipping_rule_amount'] - $data['discount_amount']);

        return $data;
    }



}
