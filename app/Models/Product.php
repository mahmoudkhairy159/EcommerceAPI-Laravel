<?php

namespace App\Models;

use App\ModelFilters\ProductFilter;
use App\Traits\UploadFileTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory, Filterable, SoftDeletes, Sluggable, UploadFileTrait;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'seo_description',
        'seo_keys',
        'image',
        'video_url',
        'vendor_id',
        'category_id',
        'brand_id',
        'price',
        'offer_price',
        'offer_start_date',
        'offer_end_date',
        'currency',
        'quantity',
        'alert_stock_quantity',
        'short_description',
        'long_description',
        'return_policy',
        'product_type',
        'approval_status',
        'created_by',
        'updated_by',
        'status',
        'serial',
    ];

    // Slug configuration
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'id'],
                'separator' => '-',
            ],
        ];
    }

    // Status constants
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    // Approval status constants
    const APPROVAL_PENDING = 0;
    const APPROVAL_APPROVED = 1;
    const APPROVAL_REJECTED = 2;

    // Image constants
    const FILES_DIRECTORY = 'products';

    protected $appends = ['image_url', "is_wish_listed", "is_carted"];

    protected function getImageUrlAttribute()
    {
        return $this->image ? $this->getFileAttribute($this->image) : null;
    }
    public function getIsCartedAttribute()
    {
        return Auth::guard('user-api')->user() &&Auth::guard('user-api')->user()->cart? $this->cartProducts()->where("cart_id", Auth::guard('user-api')->user()->cart->id)->exists() : false;
    }

    public function getIsWishListedAttribute()
    {
        return Auth::guard('user-api')->user() &&  Auth::guard('user-api')->user()->wishlist? $this->wishlistProducts()->where("wishlist_id", Auth::guard('user-api')->user()->wishlist->id)->exists() : false;
    }

    public function modelFilter()
    {
        return $this->provideFilter(ProductFilter::class);
    }

    /******************* Relationships *********************/

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
                    ->withPivot('price','tax','vendor_id', 'quantity','variants','variantsTotalPrice')
                    ->withTimestamps();
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
    public function flashSaleProduct()
    {
        return $this->hasOne(FlashSaleProduct::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function wishlistProducts()
    {
        return $this->hasMany(WishlistProduct::class);
    }

    /******************* End Relationships *********************/
}
