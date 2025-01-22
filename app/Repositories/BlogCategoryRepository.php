<?php

namespace App\Repositories;

use App\Traits\SoftDeletableTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use App\Models\BlogCategory;
use Prettus\Repository\Eloquent\BaseRepository;

class BlogCategoryRepository extends BaseRepository
{
    use SoftDeletableTrait;


    public function model()
    {
        return BlogCategory::class;
    }
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

    private function makeDefaultSortByColumn($column = 'created_at')
    {
        request()->merge([
            'sortBy' => request()->input('sortBy', $column)
        ]);
    }

}
