<?php

namespace App\Repositories;

use App\Models\ReferenceCustomer;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ReferenceCustomerRepository extends BaseRepository
{
    use UploadFileTrait;
    public function model()
    {
        return ReferenceCustomer::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->first();
    }

    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), ReferenceCustomer::FILES_DIRECTORY);
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

            $model = $this->model->findOrFail($id);
            if (request()->hasFile('image')) {
                if ($model->image) {
                    $this->deleteFile($model->image, ReferenceCustomer::FILES_DIRECTORY);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), ReferenceCustomer::FILES_DIRECTORY);
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
            if ($model->image) {
                $this->deleteFile($model->image, ReferenceCustomer::FILES_DIRECTORY);
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
