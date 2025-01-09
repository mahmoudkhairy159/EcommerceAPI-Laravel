<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FlashSaleProduct\StoreFlashSaleProductRequest;
use App\Http\Requests\Api\FlashSaleProduct\UpdateFlashSaleProductRequest;
use App\Http\Resources\Api\FlashSaleProduct\FlashSaleProductCollection;
use App\Http\Resources\Api\FlashSaleProduct\FlashSaleProductResource;
use App\Repositories\FlashSaleProductRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class FlashSaleProductController extends Controller
{
    use ApiResponseTrait;
    protected $flashSaleProductRepository;
    protected $_config;
    protected $guard;
    public function __construct(FlashSaleProductRepository $flashSaleProductRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->flashSaleProductRepository = $flashSaleProductRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);

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
            $data = $this->flashSaleProductRepository->getAllActive()->paginate();
            return $this->successResponse(new FlashSaleProductCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getShowAtHome()
    {
        try {
            $data = $this->flashSaleProductRepository->getShowAtHome()->get();
            return $this->successResponse( FlashSaleProductResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }




}
