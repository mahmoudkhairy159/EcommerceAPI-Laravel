<?php

namespace App\Models;

use App\ModelFilters\ProductFilter;
use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory, Filterable, Sluggable,UploadFileTrait;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'image',
        'video_url',
        'rank',
        'status',
        'selling_price',
        'cost_price',
        'discount',
        'currency',
        'quantity',
        'alert_stock_quantity',
        'order_type',
        'short_description',
        'long_description',
        'return_policy',
        'rate',
        'category_id',
        'brand_id',
        'is_featured',
    ];
    //slug
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'id'],
                'separator' => '-',
            ],
        ];
    }
    //slug
    //status
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    //status
    //image
    const FILES_DIRECTORY = 'products';

    protected $appends = ['image_url', "is_wish_listed", "is_carted"];
    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }
    public function getIsCartedAttribute()
    {
        return Auth::guard('user-api')->user() ? $this->cartProducts()->where("cart_id", Auth::guard('user-api')->user()->cart->id)->exists() : false;

    }

    public function getIsWishListedAttribute()
    {
        return Auth::guard('user-api')->user() ? $this->cartProducts()->where("cart_id", Auth::guard('user-api')->user()->cart->id)->exists() : false;
        // return $this->Wishlists()->where("user_id", Auth::guard('user-api')->id())->exists();
    }
    //image
    public function modelFilter()
    {
        return $this->provideFilter(ProductFilter::class);
    }
    //image
    /*******************Relationships********************* */

    public function Category()
    {

        return $this->belongsTo(Category::class);
    }

    public function Brand()
    {

        return $this->belongsTo(Brand::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function orders()
    {

        return $this->belongsToMany(Order::class, "order_products");
    }
    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_product_id');
    }
    public function accessories()
    {
        return $this->belongsToMany(Product::class, 'product_accessories', 'product_id', 'accessory_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'product_service');
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function wishlists()
    {

        return $this->hasMany(Wishlist::class);
    }

    /*******************End Relationships********************* */

}
