<?php

namespace App\Repositories;

use App\Models\UserAddress;
use App\Models\UserAddressImage;
use App\Traits\SoftDeletableTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class UserAddressRepository extends BaseRepository
{
    public function model()
    {
        return UserAddress::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getAllByUserId($userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->filter(request()->all())
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
            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function updateOneByUser(array $data, int $id)
    {
        try {
            DB::beginTransaction();

            $model = $this->model
                ->where('user_id', auth()->guard('user-api')->id())
                ->findOrFail($id);
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
    public function deleteOneByUser(int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model
                ->where('user_id', auth()->guard('user-api')->id())
                ->findOrFail($id);

            $deleted = $model->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }




}
