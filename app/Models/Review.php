<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ModelFilters\ReviewFilter;
use App\Models\User;

class Review extends Model
{
    use HasFactory,Filterable,SoftDeletes;
    protected $fillable = ['comment', 'rate','user_id','status'];
    public function modelFilter()
    {
        return $this->provideFilter( ReviewFilter::class);
    }
    public function reviewable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
