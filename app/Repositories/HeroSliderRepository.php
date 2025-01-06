<?php

namespace App\Repositories;

use App\Models\HeroSlider;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class HeroSliderRepository extends BaseRepository
{
    use UploadFileTrait;
    public function model()
    {
        return HeroSlider::class;
    }

    public function getAll()
    {
        return $this->model->orderBy('created_at', 'desc');
    }
    public function getAllActive()
    {
        return $this->model->where('status',HeroSlider::STATUS_ACTIVE)->orderBy('serial', 'asc');
    }

    public function getOneActiveById(int $id)
    {
        return $this->model
            ->where('status', HeroSlider::STATUS_ACTIVE)
            ->where('id', $id)
            ->first();
    }
    public function createOne($data)
    {
        try {
            DB::beginTransaction();
            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->image, HeroSlider::FILES_DIRECTORY);
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

    public function updateOne($data, $id)
    {
        try {
            DB::beginTransaction();

            $model = $this->model->findOrFail($id);
            if (request()->hasFile('image')) {
                if ($model->image) {
                    $this->deleteFile($model->image);
                }
                $data['image'] = $this->uploadFile(request()->image, HeroSlider::FILES_DIRECTORY);
            }
            $updated = $model->update($data);
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function deleteOne($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->findOrFail($id);
            if ($model->image) {
                $this->deleteFile($model->image);
            }
            $deleted = $model->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }

}
