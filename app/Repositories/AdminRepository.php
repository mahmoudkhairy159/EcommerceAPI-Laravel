<?php

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class AdminRepository extends BaseRepository
{
    public function model()
    {
        return Admin::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();
            $role_id = $data['role_id'];
            unset($data['role_id']);
            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), Admin::FILES_DIRECTORY);
            }
            $created = $this->model->create($data);
            $created->roles()->attach([$role_id]);
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
            $admin = $this->model->findOrFail($id);
            $role_id = $data['role_id'];
            unset($data['role_id']);
            if (request()->hasFile('image')) {
                if ($admin->image) {
                    $this->deleteFile($admin->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), Admin::FILES_DIRECTORY);
            }
            $updated = $admin->update($data);
            $admin->roles()->sync([$role_id]);
            DB::commit();

            return $admin->refresh();
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    //delete by admin
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();

            $admin = $this->model->findOrFail($id);
            // if ($admin->image) {
            //     $this->deleteFile($admin->image);
            // }
            // $deleted = $admin->delete();
            $admin->status = Admin::STATUS_INACTIVE;
            $deleted = $admin->save();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
}
