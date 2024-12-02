<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class WishlistRepository extends BaseRepository
{
    public function model()
    {
        return Wishlist::class;
    }

    /**
     * Get all wishlists with their items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->with('items');
    }

    /**
     * Get the wishlist for the authenticated user.
     *
     * @return Wishlist
     */
    public function getWishlist()
    {
        $userId = Auth::guard('user-api')->id();
        return $this->getWishlistByUserId($userId);
    }

    /**
     * Get the wishlist by user ID, create one if it doesn't exist.
     *
     * @param int $userId
     * @return Wishlist
     */
    public function getWishlistByUserId($userId)
    {
        return $this->model->firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Add a product to the authenticated user's wishlist.
     *
     * @param array $data
     * @return WishlistItem|bool
     */
    public function addProduct(array $data)
    {
        DB::beginTransaction();

        try {
            $wishlist = $this->getWishlist();

            // Check if the product is already in the wishlist
            $existingItem = $wishlist->items()->where('product_id', $data['product_id'])->first();
            if (!$existingItem) {
                $wishlistItem = new WishlistItem([
                    'product_id' => $data['product_id'],
                ]);

                $wishlist->items()->save($wishlistItem);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * Remove a product from the authenticated user's wishlist.
     *
     * @param int $productId
     * @return bool
     */
    public function removeProduct($productId)
    {
        DB::beginTransaction();

        try {
            $wishlist = $this->getWishlist();
            $wishlist->items()->where('product_id', $productId)->delete();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Empty a specific wishlist by its ID.
     *
     * @param int $wishlistId
     * @return bool
     */
    public function emptyWishlist($wishlistId)
    {
        DB::beginTransaction();

        try {
            WishlistItem::where('wishlist_id', $wishlistId)->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Get the products in the authenticated user's wishlist.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProducts()
    {
        $wishlist = $this->getWishlist();
        return $wishlist->items()->with('product');
    }

}
