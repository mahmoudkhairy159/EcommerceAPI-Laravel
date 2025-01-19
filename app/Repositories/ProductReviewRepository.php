<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\ProductReview;
use App\Models\Service;
use App\Traits\SoftDeletableTrait;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductReviewRepository extends BaseRepository
{
    public function model()
    {
        return ProductReview::class;
    }
    public function getByUserId($userId)
    {
        return  $this->model
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])->where('user_id', $userId)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getByProductId($productId)
    {
        return  $this->model
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])
            ->where('product_id', $productId)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getByVendorId($vendorId)
    {
        return  $this->model
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])
            ->where('vendor_id', $vendorId)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }

    public function updateOne(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->where('user_id', $data['user_id'])->findOrFail($id);
            $updated = $model->update($data);
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }


}
