<?php

namespace App\Repositories;

use App\Models\Advertisement;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class AdvertisementRepository extends BaseRepository
{
    public function model()
    {
        return Advertisement::class;
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
            ->where('status', Advertisement::STATUS_ACTIVE)
            ->inRandomOrder();
    }
    public function getAllActiveByPosition($position)
    {
        return $this->model
            ->where('position', $position)
            ->where('status', Advertisement::STATUS_ACTIVE)
            ->inRandomOrder();
    }
    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), Advertisement::FILES_DIRECTORY);
            }
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
            $advertisement = $this->model->findOrFail($id);
            if (request()->hasFile('image')) {
                if ($advertisement->image) {
                    $this->deleteFile($advertisement->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), Advertisement::FILES_DIRECTORY);
            }
            $updated = $advertisement->update($data);
            DB::commit();

            return $advertisement->refresh();
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    //delete by advertisement
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();

            $advertisement = $this->model->findOrFail($id);
            if ($advertisement->image) {
                $this->deleteFile($advertisement->image);
            }
            $deleted = $advertisement->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function trackClick($id)
    {
        try {
            DB::beginTransaction();
            $advertisement = $this->model->findOrFail($id);
            $advertisement->increment('clicks');
            DB::commit();
            return true;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }

    }
    public function changeStatus(int $id)
    {
        try {
            DB::beginTransaction();
            $advertisement = $this->model->findOrFail($id);
            $advertisement->status = $advertisement->status == Advertisement::STATUS_ACTIVE ? Advertisement::STATUS_INACTIVE : Advertisement::STATUS_ACTIVE;
            $updated = $advertisement->save();
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return false;
        }
    }
}
