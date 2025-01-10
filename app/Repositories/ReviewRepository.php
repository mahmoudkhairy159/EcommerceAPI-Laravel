<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\Service;
use Prettus\Repository\Eloquent\BaseRepository;

class ReviewRepository extends BaseRepository
{
    public function model()
    {
        return Review::class;
    }
    public function getByUserId($user_id)
    {
        return  $this->model
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])->where('user_id', $user_id)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getByProductId($product_id)
    {
        return  $this->model
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])
            ->where('reviewable',Product::class)
            ->where('reviewable_id', $product_id)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getByServiceId($service_id)
    {
        return  $this->model
            ->with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])
            ->where('reviewable',Service::class)
            ->where('reviewable_id', $service_id)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }

    /***********Trashed model SoftDeletes**************/
    public function getOnlyTrashed()
    {
        return $this->model
            ->onlyTrashed()
            ->filter(request()->all())
            ->orderBy('deleted_at', 'desc');
    }
    public function forceDelete(int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->findOrFail($id);
            $deleted = $model->forceDelete();
            DB::commit();
            return  $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function restore(int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->findOrFail($id);
            $restored = $model->restore();
            DB::commit();
            return  $restored;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
    /***********Trashed model SoftDeletes**************/
}
