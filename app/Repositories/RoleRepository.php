<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }
    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();
            $permissions = $data['permissions'];
            unset($data['permissions']);
            $created = $this->model->create($data);
            $created->permissions()->attach($permissions);
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
            $permissions = $data['permissions'];
            unset($data['permissions']);
            $updated = $model->update($data);
            $model->permissions()->sync($permissions);
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
}
