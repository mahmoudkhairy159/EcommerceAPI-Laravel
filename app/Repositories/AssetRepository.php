<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class AssetRepository extends BaseRepository
{
    use UploadFileTrait;
    protected $model;

    public function model()
    {
        return Asset::class;
    }
    public function getAll()
    {
        return $this->model->paginate();
    }

    public function getOne($asset_name)
    {
        return $this->model
            ->where('name', $asset_name)
            ->first();
    }

    public function updateOne($asset_name, $request)
    {
        try {
            DB::beginTransaction();

            $model = $this->model->where('name', $asset_name)->first();
            if (request()->hasFile('image')) {
                if ($model->image) {
                    $this->deleteFile($model->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), Asset::FILES_DIRECTORY);
            }

            $updated = $model->update($data);
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }

    }

}
