<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cart\UpdateUserCartRequest;
use App\Http\Resources\Admin\Cart\CartCollection;
use App\Http\Resources\Admin\Cart\CartResource;
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
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->cartRepository = $cartRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,carts-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,carts-create'])->only(['store']);
        $this->middleware(['ability:admin,carts-update'])->only(['update']);
        $this->middleware(['ability:admin,carts-delete'])->only(['removeFromCart']);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $carts = $this->cartRepository->getAll()->paginate();
            return $this->successResponse(new CartCollection($carts));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function viewUserCart($userId)
    {
        try {
            $cart = $this->cartRepository->getCartByUserId($userId);
            return $this->successResponse(new CartResource($cart));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function updateUserCart(UpdateUserCartRequest $request, $userId)
    {
        try {
            $data = $request->validated();
            $updated = $this->cartRepository->updateUserCart($userId, $data);

            if ($updated) {
                return $this->messageResponse(
                    __("app.carts.updated-successfully"),
                    true,
                    200
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
    public function removeFromCart($userId, $productId)
    {
        try {
            $removed = $this->cartRepository->removeProductByAdmin($userId, $productId);
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

}
