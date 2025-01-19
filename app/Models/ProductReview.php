<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\ModelFilters\ReviewFilter;
use App\Models\User;

class ProductReview extends Model
{
    use HasFactory,Filterable;
    protected $fillable = ['product_id','user_id','vendor_id','review', 'rating','status'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
