<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes,Filterable;
    protected $fillable = ['blog_id', 'user_id', 'content', 'parent_comment_id'];

    public function scopeTopLevel($query)
    {
        return  $query->where('parent_comment_id', null)->where('deleted_at', null)->whereHas('user', function ($query) {
            $query->active();
        });
    }
    public function scopeForBlog($query,  $blogId)
    {
        return $query->where('blog_id', $blogId);
    }
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Relationship: Blog associated with the comment.
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Relationship: User who made the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function parent_comment()
    {
        return $this->belongsTo(BlogComment::class, 'parent_comment_id');
    }

    /**
     * Get replies for the comment.
     */
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_comment_id');
    }
}
