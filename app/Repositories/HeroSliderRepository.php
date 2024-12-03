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
        return $this->model->orderBy('rank', 'asc');
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
                    $this->deleteFile($model->image, HeroSlider::FILES_DIRECTORY);
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
                $this->deleteFile($model->image, HeroSlider::FILES_DIRECTORY);
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
