<?php

namespace App\Repositories;

use App\Models\Page;
use App\Traits\UploadFileTrait;
use Prettus\Repository\Eloquent\BaseRepository;

class PageRepository extends BaseRepository
{
    use UploadFileTrait;
    public function model()
    {
        return Page::class;
    }
    public function getAll()
    {
        return $this->model
            ->select('id', 'name','slug');
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->with('assets')
            ->first();
    }

}
