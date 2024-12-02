<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    protected $orderRepository;
    protected $cartRepository;
    protected $orderProductRepository;

    public function __construct(OrderRepository $orderRepository, CartRepository $cartRepository, OrderProductRepository $orderProductRepository)
    {

        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->orderProductRepository = $orderProductRepository;
    }
    public function purchaseOrder(array $data)
    {
        DB::beginTransaction();

        try {
            // Get the current user's cart
            $cart = $this->cartRepository->getCartByUserId($data['user_id']);
            $cartItems = $cart->items;
            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }
            // Calculate total price
            $data['total_price'] = $cartItems->sum(function ($cartItem) {
                return $cartItem->quantity * $cartItem->product->selling_price;
            });
            $data['order_date'] = Carbon::now();

            // Create a new order
            $order = $this->orderRepository->create($data);

            // Move products from cart to order
            foreach ($cartItems as $cartItem) {
                $this->orderProductRepository->create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'selling_price' => $cartItem->product->selling_price,
                    'cost_price' => $cartItem->product->cost_price,
                ]);
            }

            // Empty the cart
            $this->cartRepository->emptyCart($cart->id);
            DB::commit();

            return $order;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
}
