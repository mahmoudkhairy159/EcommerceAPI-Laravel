<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Wishlist\AddToWishlistRequest;
use App\Http\Resources\Admin\WishlistProduct\WishlistProductCollection;
use App\Repositories\WishlistRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    use ApiResponseTrait;
    protected $WishlistRepository;
    protected $_config;
    protected $guard;
    public function __construct(WishlistRepository $WishlistRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->WishlistRepository = $WishlistRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
    }

    public function viewWishlist()
    {
        try {
            $userId=auth()->id();
            $data = $this->WishlistRepository->getWishlistProducts($userId)->paginate();
            return $this->successResponse(new WishlistProductCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function addToWishlist(AddToWishlistRequest $request)
    {
        try {
            $data = $request->validated();
            $userId=auth()->id();
            $added = $this->WishlistRepository->addProduct($data,$userId);

            if ($added) {
                return $this->messageResponse(
                    __("app.wishlists.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.wishlists.created-failed"),
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

    public function removeFromWishlist($productId)
    {
        try {
            $userId=auth()->id();

            $removed = $this->WishlistRepository->removeProduct($productId,  $userId);
            if ($removed) {
                return $this->messageResponse(
                    __("app.wishlists.deleted-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.wishlists.deleted-failed"),
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
