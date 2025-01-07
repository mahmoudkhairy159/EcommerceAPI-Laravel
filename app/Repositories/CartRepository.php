<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Carbon\Carbon;
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
        return $this->model->with('cartProducts');
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

    //update to cart
    public function updateUserCart($userId, $data)
    {
        DB::beginTransaction();

        try {
            // Retrieve or create the user's cart
            $cart = $this->getCartByUserId($userId);
            $products = $data['products'];

            foreach ($products as $productData) {
                // Fetch the product to ensure it exists
                $product = Product::findOrFail($productData['id']);

                // Check if the product already exists in the cart
                $cartProduct = $cart->cartProducts()->where('product_id', $productData['id'])->first();
                // Calculate the new quantity, tax, and subtotal
                $quantity = $data['quantity'];
                // Handle pricing policies
                $pricingPolicy = core()->getPricingPolicy();
                $tax = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->tax : $product->tax;
                $price = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->price : $product->price;
                $totalTax = $this->calculateTax($tax, $quantity);
                $subtotal = $this->calculateSubtotal($product->price, $totalTax, $quantity);
                $cartProduct = $this->saveCartProduct($cartProduct, $product->id,$product->name, $price, $totalTax, $subtotal, $quantity, $cart);

            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    // End update to cart



    //add to cart
    public function addProduct(array $data)
    {
        DB::beginTransaction();

        try {
            // Fetch the cart and product
            $cart = $this->getCart();
            $product = Product::findOrFail($data['product_id']);

            // Fetch existing cart product if it exists
            $cartProduct = $cart->cartProducts()->where('product_id', $data['product_id'])->first();

            // Calculate the new quantity, tax, and subtotal
            $quantity = $cartProduct ? $cartProduct->quantity + $data['quantity'] : $data['quantity'];
            // Handle pricing policies
            $pricingPolicy = core()->getPricingPolicy();
            $tax = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->tax : $product->tax;
            $price = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->price : $product->price;
            $totalTax = $this->calculateTax($tax, $quantity);
            $subtotal = $this->calculateSubtotal($product->price, $totalTax, $quantity);
            $cartProduct = $this->saveCartProduct($cartProduct, $product->id,$product->name ,$price, $totalTax, $subtotal, $quantity, $cart);
            DB::commit();
            return $cartProduct;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }



    private function calculateTax($tax, $quantity)
    {
        return $tax * $quantity;
    }

    // Calculate subtotal
    private function calculateSubtotal($price, $totalTax, $quantity)
    {
        return ($price * $quantity) + $totalTax;
    }

    // Handle pricing based on the selected pricing policy


    // Handle static pricing
    private function saveCartProduct($cartProduct, $productId,$productName, $price, $totalTax, $subtotal, $quantity, $cart)
    {
        if ($cartProduct) {
            // Update the existing cart product
            $cartProduct->update([
                'name' => $productName,
                'price' => $price,
                'tax' => $totalTax,
                'subtotal' => $subtotal,
                'quantity' => $quantity,
                'expires_at' =>request('expires_at', Carbon::now()->addDays(7)->toDateTimeString()),
            ]);
        } else {
            // Create a new cart product entry if it doesn't exist
            $cartProduct = new CartProduct([
                'product_id' => $productId,
                'name' => $productName,
                'price' => $price,
                'tax' => $totalTax,
                'subtotal' => $subtotal,
                'quantity' => $quantity,
                'expires_at' =>request('expires_at', Carbon::now()->addDays(7)->toDateTimeString()),
            ]);
            $cart->cartProducts()->save($cartProduct);
        }

        return $cartProduct;
    }

    // end add to cart



    public function removeProduct($productId)
    {
        DB::beginTransaction();

        try {
            $cart = $this->getCart();
            $cart->cartProducts()->where('id', $productId)->delete();

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
            $cart->cartProducts()->where('id', $productId)->delete();

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
            // Retrieve the cart
            $cart = $this->getCart();

            // Find the product in the cart
            $cartProduct = $cart->cartProducts()->where('product_id', $productId)->firstOrFail();

            // Fetch the product details
            $product = Product::findOrFail($productId);

            // Calculate the new quantity, tax, and subtotal
            $quantity = $cartProduct ? $cartProduct->quantity + $data['quantity'] : $data['quantity'];
            // Handle pricing policies
            $pricingPolicy = core()->getPricingPolicy();
            $tax = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->tax : $product->tax;
            $price = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->price : $product->price;
            $totalTax = $this->calculateTax($tax, $quantity);
            $subtotal = $this->calculateSubtotal($product->price, $totalTax, $quantity);
            $cartProduct = $this->saveCartProduct($cartProduct, $product->id,$product->name,$price, $totalTax, $subtotal, $quantity, $cart);
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
        $cartProducts = $cart->cartProducts()->with('product')
            ->when(request('name'), function ($query) {
                $name = request('name');
                $query->where('name', 'like', "%{$name}%");
            })->paginate();  // Eager load products


        foreach ($cartProducts as $cartProduct) {
            $product = $cartProduct->product;  // Use the loaded product
            // Calculate the new quantity, tax, and subtotal
            $quantity = $cartProduct->quantity;
            // Handle pricing policies
            $pricingPolicy = core()->getPricingPolicy();
            $tax = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->tax : $product->tax;
            $price = $cartProduct && $pricingPolicy == 'static' ? $cartProduct->price : $product->price;
            $totalTax = $this->calculateTax($tax, $quantity);
            $subtotal = $this->calculateSubtotal($product->price, $totalTax, $quantity);
            $cartProduct = $this->saveCartProduct($cartProduct, $product->id, $product->name,$price, $totalTax, $subtotal, $quantity, $cart);
        }

        return [
            'cartProducts' => $cartProducts,
            'sum_price' => $cartProducts->sum('price'),
            'sum_tax' => $cartProducts->sum('tax'),
            'sum_subtotal' => $cartProducts->sum('subtotal'),
            'sum_quantity' => $cartProducts->sum('quantity'),

        ];
    }



}
