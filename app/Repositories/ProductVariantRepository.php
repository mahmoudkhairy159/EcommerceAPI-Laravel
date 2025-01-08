<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\ProductVariant;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductVariantRepository extends BaseRepository
{
    public function model()
    {
        return ProductVariant::class;
    }
    public function getByProductId($product_id)
    {
        return $this->model
            ->where('product_id', $product_id);

    }
    public function getActiveByProductId($product_id)
    {
        return $this->model
            ->where('product_id', $product_id)
            ->where('status', ProductVariant::STATUS_ACTIVE);

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
            ->where('status', ProductVariant::STATUS_ACTIVE)
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
            dd($th->getMessage());
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
