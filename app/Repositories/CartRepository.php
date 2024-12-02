<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class CartRepository extends BaseRepository
{
    public function model()
    {
        return Cart::class;
    }

    public function getAll()
    {
        return $this->model->with('items');
    }

    public function getCart()
    {
        $userId = Auth::guard('user-api')->id();
        return $this->getCartByUserId($userId);
    }

    public function getCartByUserId($userId)
    {
        return $this->model->with('items')->firstOrCreate(['user_id' => $userId]);
    }

    public function updateUserCart($userId, $data)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCartByUserId($userId);
            $products = $data['products'];

            foreach ($products as $productData) {
                $product = Product::findOrFail($productData['id']);
                $cartProduct = $cart->items()->where('id', $productData['id'])->first();

                if ($cartProduct) {
                    $cartProduct->quantity = $productData['quantity'];
                    $cartProduct->save();
                } else {
                    $cartProduct = new CartProduct([
                        'product_id' => $productData['id'],
                        'quantity' => $productData['quantity'],
                    ]);
                    $cart->items()->save($cartProduct);
                }
            }
            // $cart->total_price = $this->calculateTotalPrice($cart); // Recalculate total price

            DB::commit();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function addProduct(array $data)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();
            $product = Product::findOrFail($data['product_id']);
            $cartProduct = $cart->items()->where('product_id', $data['product_id'])->first();

            if ($cartProduct) {
                $cartProduct->quantity += $data['quantity'];
                $cartProduct->save();
            } else {
                $cartProduct = new CartProduct([
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                ]);
                $cart->items()->save($cartProduct);
            }

            $cart->save();

            DB::commit();
            return $cartProduct;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function removeProduct($productId)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();
            $cart->items()->where('id', $productId)->delete();

            $cart->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function removeProductByAdmin($userId, $productId)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCartByUserId($userId);
            $cart->items()->where('id', $productId)->delete();

            $cart->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function emptyCart($cart_id)
    {
        try {
            DB::beginTransaction();
            CartProduct::where('cart_id', $cart_id)->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateProductQuantity($productId, array $data)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();
            $cartProduct = $cart->items()->findOrFail($productId);

            if ($cartProduct) {
                $cartProduct->quantity = $data['quantity'];
                $cartProduct->save();
            }

            $cart->save();

            DB::commit();
            return $cartProduct;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getProducts()
    {
        $cart = $this->getCart();
        return $cart->items()->with('product');
    }

}
