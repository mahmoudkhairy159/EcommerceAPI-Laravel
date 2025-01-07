<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class UpdateOrderService
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
    public function updateOrder(array $data, Order $order)
    {
        DB::beginTransaction();

        try {

            if (isset($data['products'])) {
                $this->orderProductRepository->deleteByOrderId($order->id);
                foreach ($data['products'] as $productData) {
                    $this->orderProductRepository->create([
                        'order_id' => $order->id,
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity'],
                        'price' => $productData['price'],
                        'cost_price' => $productData['cost_price'],

                    ]);
                }
            }
            $this->orderRepository->calculateTotalPrice($order);
            $updated = $this->orderRepository->update($data, $order->id);
            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
}
