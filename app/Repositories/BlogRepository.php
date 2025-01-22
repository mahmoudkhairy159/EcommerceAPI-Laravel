<?php

namespace App\Repositories;

use App\Traits\SoftDeletableTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
use Prettus\Repository\Eloquent\BaseRepository;

class BlogRepository extends BaseRepository
{
    use SoftDeletableTrait;
    use UploadFileTrait;


    public function model()
    {
        return Blog::class;
    }
    /*****************************************Retrieving For Admins **************************************/
    public function getAll()
    {
        $this->makeDefaultSortByColumn();
        return $this->model
            ->filter(request()->all());
    }
    public function getOneById($id)
    {
        return $this->model
            ->where('id', $id)
            ->first();
    }



    public function getAllActive()
    {
        $this->makeDefaultSortByColumn();

        return $this->model
            ->where('status', Blog::STATUS_ACTIVE)
            ->filter(request()->all());
    }

    public function getActiveOneById($id)
    {
        return $this->model
            ->where('id', $id)
            ->where('status', Blog::STATUS_ACTIVE)
            ->first();
    }
    public function getActiveOneBySlug($slug)
    {
        return $this->model
            ->where('slug', $slug)
            ->where('status', Blog::STATUS_ACTIVE)
            ->first();
    }


    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();
            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), Blog::FILES_DIRECTORY);
            }
            $created = $this->create($data);
            DB::commit();
            return $created->refresh();
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
            $userId = auth()->id();
            $blog = $this->model->findOrFail($id);
            if (request()->hasFile('image')) {
                if ($blog->image) {
                    $this->deleteFile($blog->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), Blog::FILES_DIRECTORY);
            }
            $updated = $blog->update($data);
            DB::commit();
            return $blog->refresh();
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return false;
        }
    }
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();
            $blog = $this->model->findOrFail($id);
            $deleted = $blog->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }









    private function makeDefaultSortByColumn($column = 'created_at')
    {
        request()->merge([
            'sortBy' => request()->input('sortBy', $column)
        ]);
    }

}
