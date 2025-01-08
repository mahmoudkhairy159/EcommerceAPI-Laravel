<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantItem;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductVariantItemRepository extends BaseRepository
{
    public function model()
    {
        return ProductVariantItem::class;
    }
    public function getByProductVariantId($product_variant_id)
    {
        return $this->model
            ->where('product_variant_id', $product_variant_id);

    }
    public function getByActiveProductVariantId($product_variant_id)
    {
        return $this->model
            ->where('product_variant_id', $product_variant_id)
            ->where('status', ProductVariantItem::STATUS_ACTIVE)
            ->where('is_default', ProductVariantItem::STATUS_ACTIVE);

    }

    public function getOneById($id)
    {
        return $this->model
            ->where('id', $id)
            ->first();
    }
    public function getActiveOneById($id)
    {
        return $this->model
        ->where('id', $id)
        ->where('status', ProductVariantItem::STATUS_ACTIVE)
        ->where('is_default', ProductVariantItem::STATUS_ACTIVE)
        ->first();

    }


    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();
            $created = $this->model->create($data);
            DB::commit();

            return $created;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function updateOne(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->findOrFail($id);
            $updated = $model->update($data);
            DB::commit();

            return $model->refresh();
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    //delete by model
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();

            $model = $this->model->findOrFail($id);
            $deleted = $model->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
}
