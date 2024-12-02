<?php

namespace App\Repositories;

use App\Models\BrandImage;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class BrandImageRepository extends BaseRepository
{
    public function model()
    {
        return BrandImage::class;
    }
    public function getByBrandId($brand_id)
    {
        return $this->model
            ->where('brand_id', $brand_id)
            ->orderBy('created_at', 'desc');
    }

    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), BrandImage::FILES_DIRECTORY);
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
            $brand = $this->model->findOrFail($id);
            if (request()->hasFile('image')) {
                if ($brand->image) {
                    $this->deleteFile($brand->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), BrandImage::FILES_DIRECTORY);
            }
            $updated = $brand->update($data);

            DB::commit();

            return $brand->refresh();
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    //delete by brand
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();

            $brand = $this->model->findOrFail($id);
            if ($brand->image) {
                $this->deleteFile($brand->image);
            }
            $deleted = $brand->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
}
