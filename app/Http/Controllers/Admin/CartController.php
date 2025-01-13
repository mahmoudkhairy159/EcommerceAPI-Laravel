<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Cart\CartCollection;
use App\Http\Resources\Admin\Cart\CartResource;
use App\Http\Resources\Admin\CartProduct\CartProductCollection;
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
        $this->middleware(['ability:admin,carts-read'])->only([ 'viewUserCart']);

    }



    public function viewUserCart($userId)
    {
            try {
                $data = $this->cartRepository->getProducts($userId);
                $data['cartProducts']=new CartProductCollection( $data['cartProducts']);
                return $this->successResponse($data);
            } catch (Exception $e) {
                return $this->errorResponse(
                    [],
                    __('app.something-went-wrong'),
                    500
                );
            }
    }



}
