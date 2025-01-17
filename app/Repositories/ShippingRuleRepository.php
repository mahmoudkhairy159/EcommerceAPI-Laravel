<?php

namespace App\Repositories;

use App\Enums\ShippingRuleTypeEnum;
use App\Models\ShippingRule;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ShippingRuleRepository extends BaseRepository
{

    public function model()
    {
        return ShippingRule::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getAllActive()
    {
        return $this->model
            ->filter(request()->all())
            ->where('status', ShippingRule::STATUS_ACTIVE)
            ->orderBy('serial', 'asc');
    }




    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();
            $created = $this->model->create($data);
            DB::commit();

            return $created;
        } catch (\Throwable $th) {
            dd( $th->getMessage());
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
            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
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



    public function changeStatus(int $id)
    {
        try {
            DB::beginTransaction();
            $shippingRule = $this->model->findOrFail($id);
            $shippingRule->status = $shippingRule->status == ShippingRule::STATUS_ACTIVE ? ShippingRule::STATUS_INACTIVE : ShippingRule::STATUS_ACTIVE;
            $updated = $shippingRule->save();
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return false;
        }
    }
    public function calculateShippingRuleAmount(ShippingRule $shippingRule)
    {
        if($shippingRule){
            return $shippingRule->cost;
        }else {
            return 0;
        }

    }

}
