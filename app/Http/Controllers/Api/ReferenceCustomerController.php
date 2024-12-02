<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ReferenceCustomer\ReferenceCustomerCollection;
use App\Http\Resources\Api\ReferenceCustomer\ReferenceCustomerResource;
use App\Repositories\ReferenceCustomerRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ReferenceCustomerController extends Controller
{
    use ApiResponseTrait;
    protected $referenceCustomerRepository;
    protected $_config;
    protected $guard;
    public function __construct(ReferenceCustomerRepository $referenceCustomerRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->referenceCustomerRepository = $referenceCustomerRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);

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
            $data = $this->referenceCustomerRepository->getAll()->paginate();
            return $this->successResponse(new ReferenceCustomerCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->referenceCustomerRepository->findOrFail($id);
            return $this->successResponse(new ReferenceCustomerResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function showBySlug(string $slug)
    {
        try {
            $data = $this->referenceCustomerRepository->findActiveBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new ReferenceCustomerResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
