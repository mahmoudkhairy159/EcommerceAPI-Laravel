<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FlashSale\FlashSaleResource;
use App\Repositories\FlashSaleRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class FlashSaleController extends Controller
{
    use ApiResponseTrait;
    protected $flashSaleRepository;
    protected $_config;
    protected $guard;
    public function __construct(FlashSaleRepository $flashSaleRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->flashSaleRepository = $flashSaleRepository;
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
            $data = $this->flashSaleRepository->getFlashSale();
            return $this->successResponse(new FlashSaleResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }












}
