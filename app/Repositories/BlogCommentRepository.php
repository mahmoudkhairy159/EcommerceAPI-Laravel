<?php

namespace App\Repositories;

use App\Traits\SoftDeletableTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Support\Facades\DB;
use App\Models\BlogComment;
use Prettus\Repository\Eloquent\BaseRepository;

class BlogCommentRepository extends BaseRepository
{
    use UploadFileTrait;
    use SoftDeletableTrait;

    public function model()
    {
        return BlogComment::class;
    }

    public function getByUserId($userId)
    {
        return  $this->model
            ->with([
                'parent_comment',
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])
            ->whereDoesntHave('parent_comment')
            ->where(function ($query) {
                // Exclude comments with parent_comment_id having a value and parent_comment being null
                $query->whereNull('parent_comment_id')
                    ->orWhereHas('parent_comment');
            })
            ->withCount(['replies' => function ($query) {
                $query->where('deleted_at', null);
            }])->byUser($userId)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getByBlogId($blogId)
    {
        return  $this->model
            ->with([
                'parent_comment',
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])
            ->whereDoesntHave('parent_comment')
            ->where(function ($query) {
                // Exclude comments with parent_comment_id having a value and parent_comment being null
                $query->whereNull('parent_comment_id')
                    ->orWhereHas('parent_comment');
            })
            ->withCount(['replies' => function ($query) {
                $query->where('deleted_at', null);
            }])->forBlog($blogId)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }




    /**********************************************comment replies ***********************************/
    public function reply(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $comment = $this->model->findOrFail($id);
            // add reply
            $data['parent_comment_id'] = $comment->parent_comment_id != null ? $comment->parent_comment_id : $comment->id;
            $data['blog_id'] = $comment->blog_id;
            $replied = $this->model->create($data);
            DB::commit();
            return $replied->refresh();
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function getRepliesByCommentId($comment_id)
    {
        return  $this->model
            ->with([
                'parent_comment',
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])
            ->where(function ($query) {
                // Exclude comments with parent_comment_id having a value and parent_comment being null
                $query->whereNull('parent_comment_id')
                    ->orWhereHas('parent_comment');
            })
            ->withCount(['replies' => function ($query) {
                $query->where('deleted_at', null);
            }])->where('parent_comment_id', $comment_id)
            ->filter(request()->all())
            ->orderBy('created_at', 'asc');
    }



    /**********************************************End comment replies ***********************************/
}
