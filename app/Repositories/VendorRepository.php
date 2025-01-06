<?php

namespace App\Repositories;

use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class VendorRepository extends BaseRepository
{
    public function model()
    {
        return Vendor::class;
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
            ->where('status', Vendor::STATUS_ACTIVE)
            ->orderBy('serial', 'asc');
    }
    public function getFeatured()
    {
        return $this->model
        ->where('status', Vendor::STATUS_ACTIVE)
        ->where('is_featured', Vendor::IS_FEATURED_ACTIVE)
        ->orderBy('serial','asc')->limit(5);
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->first();
    }
    public function findActiveBySlug(string $slug)
    {

        return $this->model
            ->where('slug', $slug)
            ->where('status', operator: Vendor::STATUS_ACTIVE)
            ->first();
    }
    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();
            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), Vendor::FILES_DIRECTORY);
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
            $vendor = $this->model->findOrFail($id);
            if (request()->hasFile('image')) {
                if ($vendor->image) {
                    $this->deleteFile($vendor->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), Vendor::FILES_DIRECTORY);
            }
            $updated = $vendor->update($data);
            DB::commit();

            return $vendor->refresh();
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    //delete by vendor
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();

            $vendor = $this->model->findOrFail($id);
            // if ($vendor->image) {
            //     $this->deleteFile($vendor->image);
            // }
            // $deleted = $vendor->delete();
            $vendor->status = Vendor::STATUS_INACTIVE;
            $deleted = $vendor->save();
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
            $user = $this->model->findOrFail($id);
            $user->status = $user->status == Vendor::STATUS_ACTIVE ? Vendor::STATUS_INACTIVE : Vendor::STATUS_ACTIVE;
            $updated = $user->save();
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return false;
        }
    }

}
