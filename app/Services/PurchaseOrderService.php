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
    protected $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        CartRepository $cartRepository,
        OrderProductRepository $orderProductRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->orderProductRepository = $orderProductRepository;
    }

    public function purchaseOrder(array $data, string $transactionId = null, OrderCalculationService $orderCalculationService)
    {
        DB::beginTransaction();
        try {
            // Calculate order amounts
            $data = $orderCalculationService->calculateOrderAmount($data);

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
                    'vendor_id' => $product->vendor_id,
                    'variants' => $cartProduct->options['variants'],
                    'variantsTotalPrice' => $cartProduct->options['variantsTotalPrice'],
                    'quantity' => $cartProduct->quantity,
                    'price' => $cartProduct->price,
                    'tax' => $cartProduct->tax,
                ]);

                // Update product quantity
                $product->quantity -= $cartProduct->quantity;
                $product->save();
            }

            // Store transaction details
            if ($transactionId != null) {
                $order->transaction()->create([
                    'user_id' => $data['user_id'],
                    'transaction_id' => $transactionId,
                    'amount' => $order->amount,
                    'payment_status' => $data['payment_status'],
                    'payment_method' => $data['payment_method'],
                    'transaction_details' => 'Transaction initiated for Order #' . $order->id
                ]);
            }


            // Empty the cart
            $this->cartRepository->emptyCart($cart->id);
            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


}
