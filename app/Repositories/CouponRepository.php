<?php

namespace App\Repositories;

use App\Enums\DiscountTypeEnum;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class CouponRepository extends BaseRepository
{

    public function model()
    {
        return Coupon::class;
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
            ->where('status', Coupon::STATUS_ACTIVE)
            ->whereDate('start_date', '<=', date('y-m-d'))
            ->whereDate('end_date', '>=', date('y-m-d'))
            ->orderBy('created_at', 'desc');
    }
    public function getOneActiveByCode(string $code)
    {
        return $this->model
        ->where('code', $code)
        ->where('status', Coupon::STATUS_ACTIVE)
        ->whereDate('start_date', '<=', date('y-m-d'))
        ->whereDate('end_date', '>=', date('y-m-d'))
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
            $coupon = $this->model->findOrFail($id);
            $coupon->status = $coupon->status == Coupon::STATUS_ACTIVE ? Coupon::STATUS_INACTIVE : Coupon::STATUS_ACTIVE;
            $updated = $coupon->save();
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return false;
        }
    }
    public function calculateCouponDiscountAmount(Coupon $coupon, $cartSumSubTotal)
    {
        if($coupon->discount_type==DiscountTypeEnum::PERCENTAGE){
            $couponDiscountAmount= ( $cartSumSubTotal* $coupon->discount /100);

        }elseif($coupon->discount_type==DiscountTypeEnum::AMOUNT){
            $couponDiscountAmount= $coupon->discount;
        }
        return  $couponDiscountAmount;

    }

}
