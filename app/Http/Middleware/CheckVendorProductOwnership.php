<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorProductOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the product ID from the route
        $productId = $request->route('product') ?? $request->route('id');
        $productId=$productId?? $request->product_id;
        // Fetch the product from the database
        $product = Product::withTrashed()->find($productId);
        // Check if the product exists and belongs to the authenticated user
        if (!$product || $product->vendor_id !== Auth::guard('vendor-api')->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Allow the request to proceed
        return $next($request);
    }
}
