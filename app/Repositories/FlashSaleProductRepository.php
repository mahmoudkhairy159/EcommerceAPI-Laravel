<?php

namespace App\Repositories;

use App\Models\FlashSaleProduct;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class FlashSaleProductRepository extends BaseRepository
{
    public function model()
    {
        return FlashSaleProduct::class;
    }
    public function getAll()
    {
        return $this->model
            ->orderBy('created_at', 'desc');
    }
    public function getAllActive()
    {
        return $this->model
            ->where('status', FlashSaleProduct::STATUS_ACTIVE)
            ->orderBy('created_at', 'desc');
    }

    public function getShowAtHome()
    {
        return $this->model
            ->where('status', FlashSaleProduct::STATUS_ACTIVE)
            ->where('show_at_home', FlashSaleProduct:: SHOW_AT_HOME_ACTIVE)
            ->orderBy('created_at', 'desc');
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
            $flashSaleProduct = $this->model->findOrFail($id);
            $updated = $flashSaleProduct->update($data);

            DB::commit();

            return $flashSaleProduct->refresh();
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    //delete by flashSaleProduct
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();
            $flashSaleProduct = $this->model->findOrFail($id);
            $deleted = $flashSaleProduct->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
}
