<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\OrderProduct;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderProductRepository extends BaseRepository
{
    public function model()
    {
        return OrderProduct::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }

    public function getByOrderId($orderId)
    {
        return $this->model
            ->where('order_id', $orderId)
            ->filter(request()->all())
            ->orderBy('created_at', 'asc');
    }
    public function deleteByOrderId($order_id)
    {
        try {
            DB::beginTransaction();
            $deleted = $this->model->where('order_id', $order_id)->delete();
            DB::commit();
            return  $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
