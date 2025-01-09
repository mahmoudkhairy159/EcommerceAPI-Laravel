<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Models\BrandImage;
use App\Traits\SoftDeletableTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class BrandRepository extends BaseRepository
{
    use UploadFileTrait;
    use SoftDeletableTrait;
    public function model()
    {
        return Brand::class;
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
            ->where('status', Brand::STATUS_ACTIVE)
            ->orderBy('serial', 'asc');
    }
    public function getFeatured()
    {
        return $this->model
        ->where('status', Brand::STATUS_ACTIVE)
        ->where('is_featured', Brand::IS_FEATURED_ACTIVE)
        ->orderBy('serial','asc')->limit(5);
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

            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), Brand::FILES_DIRECTORY);
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
            if (request()->hasFile('image')) {
                if ($model->image) {
                    $this->deleteFile($model->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), Brand::FILES_DIRECTORY);
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
    public function updateSerial(array $data, int $id)
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
