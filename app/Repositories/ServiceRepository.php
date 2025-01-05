<?php

namespace App\Repositories;

use App\Models\Service;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ServiceRepository extends BaseRepository
{
    use UploadFileTrait;
    public function model()
    {
        return Service::class;
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
            ->where('status', Service::STATUS_ACTIVE)
            ->orderBy('serial', 'desc');
    }
    public function getFeaturedServices()
    {
        return $this->model
            ->filter(request()->all())
            ->with(['category'])
            ->where('status', Service::STATUS_ACTIVE)
            ->limit(4)
            ->inRandomOrder();
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->first();
    }
    public function findActiveBySlug(string $slug)
    {

        return $this->model
            ->where('slug', $slug)
            ->where('status', Service::STATUS_ACTIVE)
            ->first();
    }

    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), Service::FILES_DIRECTORY);
            }
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
            if (request()->hasFile('image')) {
                if ($model->image) {
                    $this->deleteFile($model->image, Service::FILES_DIRECTORY);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), Service::FILES_DIRECTORY);
            }
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
            // if ($model->image) {
            //     $this->deleteFile($model->image);
            // }
            $deleted = $model->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
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
            if ($model->image) {
                $this->deleteFile($model->image, Service::FILES_DIRECTORY);
            }
            $deleted = $model->forceDelete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function restore(int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->withTrashed()->findOrFail($id);
            $restored = $model->restore();
            DB::commit();
            return $restored;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
    /***********Trashed model SoftDeletes**************/
    public function changeStatus(int $id)
    {
        try {
            DB::beginTransaction();
            $service = $this->model->findOrFail($id);
            $service->status = $service->status == Service::STATUS_ACTIVE ? Service::STATUS_INACTIVE : Service::STATUS_ACTIVE;
            $updated = $service->save();
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return false;
        }
    }
    public function updateRank(array $data, int $id)
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
}
