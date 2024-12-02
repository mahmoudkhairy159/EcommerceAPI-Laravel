<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\Cart\UpdateProductQuantityRequest;
use App\Http\Resources\Api\CartProduct\CartProductCollection;
use App\Repositories\CartRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ApiResponseTrait;
    protected $cartRepository;
    protected $_config;
    protected $guard;
    public function __construct(CartRepository $cartRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->cartRepository = $cartRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
    }

    public function viewCart()
    {
        try {
            $data = $this->cartRepository->getProducts()->paginate();
            return $this->successResponse(new CartProductCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function addToCart(AddToCartRequest $request)
    {
        try {
            $data = $request->validated();
            $added = $this->cartRepository->addProduct($data);

            if ($added) {
                return $this->messageResponse(
                    __("app.carts.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.carts.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function removeFromCart($productId)
    {
        try {
            $removed = $this->cartRepository->removeProduct($productId);
            if ($removed) {
                return $this->messageResponse(
                    __("app.carts.deleted-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.carts.deleted-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function updateProductQuantity(UpdateProductQuantityRequest $request, $productId)
    {
        try {
            $data = $request->validated();
            $updated = $this->cartRepository->updateProductQuantity($productId, $data);
            if ($updated) {
                return $this->messageResponse(
                    __("app.carts.updated-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.carts.updated-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
