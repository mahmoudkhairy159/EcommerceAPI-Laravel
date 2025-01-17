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

class PurchaseOrderService
{
    protected $orderRepository;
    protected $cartRepository;
    protected $orderProductRepository;
    protected $userAddressRepository;
    protected $couponRepository;
    protected $shippingRuleRepository;
    protected $productRepository;


    public function __construct(
        OrderRepository $orderRepository,
        UserAddressRepository $userAddressRepository,
        CartRepository $cartRepository,
        OrderProductRepository $orderProductRepository,
        CouponRepository $couponRepository,
        ShippingRuleRepository $shippingRuleRepository,
        ProductRepository $productRepository,


    ) {

        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->couponRepository = $couponRepository;
        $this->shippingRuleRepository = $shippingRuleRepository;

    }
    public function purchaseOrder(array $data, string $transactionId)
    {
        DB::beginTransaction();
        try {

            $data['sub_total'] = $this->cartRepository->getCartSumSubTotal($data['user_id']);
            $data['order_address'] = $this->userAddressRepository->find($data['user_address_id']);
            $data['shipping_rule'] = $this->shippingRuleRepository->find($data['shipping_rule_id']);
            $data['coupon'] = isset($data['code']) ? $this->couponRepository->getOneActiveByCode($data['code']) : null;

            if (!$data['coupon'] || $data['coupon']->quantity <= $data['coupon']->total_used) {
                $data['discount_amount'] = 0;
            } else {
                $data['discount_amount'] = $this->couponRepository->calculateCouponDiscountAmount(  $data['coupon'], $data['sub_total']);
            }
            $data['shipping_rule_amount'] = $this->shippingRuleRepository->calculateShippingRuleAmount($data['shipping_rule']);
            $data['amount'] = round($data['sub_total'] + $data['shipping_rule_amount'] - $data['discount_amount']);
            $data['status'] = Order::STATUS_PENDING;

            // Create a new order
            $order = $this->orderRepository->create($data);

            $cart = $this->cartRepository->getCartByUserId($data['user_id']);

            // Move products from cart to order
            foreach ($cart->cartProducts as $cartProduct) {
                $product = $this->productRepository->find($cartProduct->product_id);

                $this->orderProductRepository->create([
                    'order_id' => $order->id,
                    'product_id' => $cartProduct->product_id,
                    'vendor_id' => $cartProduct->vendor_id,
                    'variants' => $cartProduct->options['variants'],
                    'variantsTotalPrice' => $cartProduct->options['variantsTotalPrice'],
                    'quantity' => $cartProduct->quantity,
                    'price' => $cartProduct->price,
                    'tax' => $cartProduct->tax,
                ]);
                // update product quantity
                $product->quantity = $product->quantity - $cartProduct->quantity;
                $product->save();
            }
            // store transaction details
            $transaction = $order->transaction()->create([
                'user_id' => $data['user_id'],
                'transaction_id' => $transactionId,
                'amount' => $order->amount,
                'payment_status' => $data['payment_status'],
                'payment_method' => $data['payment_method'],

                'transaction_details' => 'Transaction initiated for Order #1'
            ]);
            // Empty the cart
            $this->cartRepository->emptyCart($cart->id);
            DB::commit();

            return $order;
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
}
