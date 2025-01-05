<?php

namespace App\Repositories;

use App\Models\Category;
use App\Traits\SoftDeletableTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class CategoryRepository extends BaseRepository
{
    use UploadFileTrait;
    use SoftDeletableTrait;

    public function model()
    {
        return Category::class;
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
            ->where('status', Category::STATUS_ACTIVE)
            ->orderBy('serial', 'asc');
    }
    public function getOneById(string $id)
    {
        return $this->model
            ->where(column: 'id', operator: $id)
            ->first();
    }
    public function getActiveOneById(string $id)
    {
        return $this->model
            ->where('slug', $id)
            ->where('status', Category::STATUS_ACTIVE)
            ->first();
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function findActiveBySlug(string $slug)
    {
        return $this->model
            ->where('slug', $slug)
            ->where('status', Category::STATUS_ACTIVE)
            ->first();
    }

    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            $data['image'] = $this->uploadAndDeleteImage(request()->file('image') ?? null);
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

            $model = $this->model->findOrFail($id);
            $data['image'] = $this->uploadAndDeleteImage(request()->file('image') ?? null, $model->image);
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

    /*********** Additional Functions **************/

    public function getByParentId(int $parentId)
    {
        return $this->model->where('parent_id', $parentId);
    }
    public function getActiveByParentId(int $parentId)
    {
        return $this->model
            ->where('status', Category::STATUS_ACTIVE)
            ->where('parent_id', $parentId);
    }


    public function getMainCategories()
    {
        return $this->model
            ->with('children') // Assumes a `children` relationship is defined in the model
            ->whereNull('parent_id');
    }
    public function getActiveMainCategories()
    {
        return $this->model
            ->with('children') // Assumes a `children` relationship is defined in the model
            ->where('status', Category::STATUS_ACTIVE)
            ->whereNull('parent_id');
    }
    public function getTreeStructure()
    {
        // Get all categories with their children relationships eager-loaded
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return $categories;
    }
    public function getActiveTreeStructure()
    {
        // Get all active categories with their active children relationships eager-loaded
        $categories = Category::with(['children' => function ($query) {
            $query->where('status', Category::STATUS_ACTIVE);
        }])
        ->where('status', Category::STATUS_ACTIVE)
        ->whereNull('parent_id')
        ->get();

        return $categories;
    }

    public function bulkUpdateStatus(array $ids, int $status)
    {
        try {
            DB::beginTransaction();

            $updated = $this->model->whereIn('id', $ids)->update(['status' => $status]);

            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    private function uploadAndDeleteImage($file = null, $existingImage = null)
    {
        if ($file) {
            if ($existingImage) {
                $this->deleteFile($existingImage, Category::FILES_DIRECTORY);
            }
            return $this->uploadFile($file, Category::FILES_DIRECTORY);
        }
        return $existingImage;
    }



    /*********** Status and Serial Management **************/

    public function changeStatus(int $id)
    {
        try {
            DB::beginTransaction();

            $category = $this->model->findOrFail($id);
            $category->status = $category->status == Category::STATUS_ACTIVE ? Category::STATUS_INACTIVE : Category::STATUS_ACTIVE;
            $updated = $category->save();

            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
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
