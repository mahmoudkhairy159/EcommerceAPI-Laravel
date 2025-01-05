<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\BrandImage;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class BrandRepository extends BaseRepository
{
    use UploadFileTrait;
    public function model()
    {
        return Brand::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->with('brandImages')
            ->orderBy('created_at', 'desc');
    }
    public function getAllActive()
    {
        return $this->model
            ->filter(request()->all())
            ->with('brandImages')
            ->where('status', Brand::STATUS_ACTIVE)
            ->orderBy('serial', 'desc');
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->with('brandImages')

            ->first();
    }
    public function findActiveBySlug(string $slug)
    {

        return $this->model
            ->where('slug', $slug)
            ->with('brandImages')
            ->where('status', Brand::STATUS_ACTIVE)
            ->first();
    }

    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            if (request()->hasFile('image1')) {
                $data['image1'] = $this->uploadFile(request()->file('image1'), Brand::FILES_DIRECTORY);
            }
            if (request()->hasFile('image2')) {
                $data['image2'] = $this->uploadFile(request()->file('image2'), Brand::FILES_DIRECTORY);
            }
            if (request()->hasFile('brand_images')) {
                $images = request()->file('brand_images');
                $uploadedImages = [];
                foreach ($images as $image) {
                    $uploadedImages[] = $this->uploadFile($image, BrandImage::FILES_DIRECTORY);
                }
            }
            $created = $this->model->create($data);
            if (isset($uploadedImages)) {
                foreach ($uploadedImages as $imagePath) {
                    BrandImage::create([
                        'brand_id' => $created->id,
                        'image' => $imagePath,
                    ]);
                }
            }
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

            $model = $this->model->findOrFail($id);
            if (request()->hasFile('image1')) {
                if ($model->image1) {
                    $this->deleteFile($model->image1, Brand::FILES_DIRECTORY);
                }
                $data['image1'] = $this->uploadFile(request()->file('image1'), Brand::FILES_DIRECTORY);
            }
            if (request()->hasFile('image2')) {
                if ($model->image2) {
                    $this->deleteFile($model->image2, Brand::FILES_DIRECTORY);
                }
                $data['image2'] = $this->uploadFile(request()->file('image2'), Brand::FILES_DIRECTORY);
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
            if ($model->image1) {
                $this->deleteFile($model->image1, Brand::FILES_DIRECTORY);
            }
            if ($model->image1) {
                $this->deleteFile($model->image2, Brand::FILES_DIRECTORY);
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
            $product = $this->model->findOrFail($id);
            $product->status = $product->status == Brand::STATUS_ACTIVE ? Brand::STATUS_INACTIVE : Brand::STATUS_ACTIVE;
            $updated = $product->save();
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
