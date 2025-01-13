<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ProductVariantItem;
use App\Traits\ProductCalculationTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class CartRepository extends BaseRepository
{
    use ProductCalculationTrait;
    public function model()
    {
        return Cart::class;
    }



    public function getCart()
    {
        $userId = Auth::guard('user-api')->id();
        return $this->getCartByUserId($userId);
    }

    public function getCartByUserId($userId)
    {
        return $this->model->with('cartProducts')->firstOrCreate(['user_id' => $userId]);
    }

    public function getProducts($userId)
    {
        $cart = $this->getCartByUserId($userId);
        $cartProducts = $cart->cartProducts()->with('product')
            ->when(request('name'), function ($query) {
                $name = request('name');
                $query->where('name', 'like', "%{$name}%");
            })->paginate();  // Eager load products
        return [
            'cartProducts' => $cartProducts,
            'sum_price' => $cartProducts->sum('price'),
            'sum_tax' => $cartProducts->sum('tax'),
            'sum_subtotal' => $cartProducts->sum('subtotal'),
            'sum_quantity' => $cartProducts->sum('quantity'),

        ];
    }



    //add to cart
    public function addProduct(array $data)
    {
        DB::beginTransaction();

        try {
            // Fetch the cart and product
            $cart = $this->getCart();
            $product = Product::findOrFail($data['product_id']);
            $variants = [];
            $variantsTotalPrice = 0;
            $product_variant_items = isset($data['product_variant_items']) ? $data['product_variant_items'] : null;

            if ($product_variant_items) {
                foreach ($product_variant_items as $item_id) {
                    $productVarianItem = ProductVariantItem::find($item_id);
                    $variants[$productVarianItem->productVariant->name]['name'] = $productVarianItem->name;
                    $variants[$productVarianItem->productVariant->name]['price'] = $productVarianItem->price;
                    $variantsTotalPrice += $productVarianItem->price;
                }
            }
            $options['variants'] = $variants;
            $options['variantsTotalPrice'] = $variantsTotalPrice;



            //checkDiscount
            if ($this->checkDiscount($product)) {
                $price = $product->offer_price;
                $totalTax = $this->calculateTax($price, $data['quantity']);
                $subtotal = $this->calculateSubtotal($price, $totalTax, $data['quantity'], $variantsTotalPrice);
            } else {
                $price = $product->price;
                $totalTax = $this->calculateTax($price, quantity: $data['quantity']);
                $subtotal = $this->calculateSubtotal($price, $totalTax, $data['quantity'], $variantsTotalPrice);
            }
            $cartProduct = $this->saveCartProduct($product->id, $product->name, $price, $totalTax, $subtotal, $data['quantity'], $cart, $options);
            DB::commit();
            return $cartProduct;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
    private function saveCartProduct($productId, $productName, $price, $totalTax, $subtotal, $quantity, $cart, $options)
    {

        $cartProduct = new CartProduct([
            'product_id' => $productId,
            'name' => $productName,
            'price' => $price,
            'tax' => $totalTax,
            'subtotal' => $subtotal,
            'quantity' => $quantity,
            'expires_at' => request('expires_at', Carbon::now()->addDays(1)->toDateTimeString()),
            'options' => $options,
        ]);
        $cart->cartProducts()->save($cartProduct);


        return $cartProduct;
    }

    // end add to cart























    public function updateProductCart($id, array $data)
    {
        DB::beginTransaction();

        try {
            // Retrieve the cart
            $cart = $this->getCart();

            // Find the product in the cart
            $cartProduct = $cart->cartProducts()->where('id', $id)->firstOrFail();

            // Fetch the product details
            $product = Product::findOrFail($cartProduct->product_id);

            $variants = [];
            $variantsTotalPrice = 0;
            $product_variant_items = isset($data['product_variant_items']) ? $data['product_variant_items'] : null;

            if ($product_variant_items) {
                foreach ($product_variant_items as $item_id) {
                    $productVarianItem = ProductVariantItem::find($item_id);
                    $variants[$productVarianItem->productVariant->name]['name'] = $productVarianItem->name;
                    $variants[$productVarianItem->productVariant->name]['price'] = $productVarianItem->price;
                    $variantsTotalPrice += $productVarianItem->price;
                }
            }
            $options['variants'] = $variants;
            $options['variantsTotalPrice'] = $variantsTotalPrice;

            //checkDiscount
            if ($this->checkDiscount($product)) {
                $price = $product->offer_price;
                $totalTax = $this->calculateTax($price, $data['quantity']);
                $subtotal = $this->calculateSubtotal($price, $totalTax, $data['quantity'], $variantsTotalPrice);
            } else {
                $price = $product->price;
                $totalTax = $this->calculateTax($price, quantity: $data['quantity']);
                $subtotal = $this->calculateSubtotal($price, $totalTax, $data['quantity'], $variantsTotalPrice);
            }
            $cartProduct = $cartProduct->update([
                'price' => $price,
                'tax' => $totalTax,
                'subtotal' => $subtotal,
                'quantity' => $data['quantity'],
                'expires_at' => request('expires_at', Carbon::now()->addDays(1)->toDateTimeString()),
                'options' => $options,
            ]);
            DB::commit();
            return $cartProduct;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }




    public function removeProduct($id)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();
            $cart->cartProducts()->where('id', $id)->delete();

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


}
