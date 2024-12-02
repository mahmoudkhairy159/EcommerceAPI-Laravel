<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReferenceCustomer\StoreReferenceCustomerRequest;
use App\Http\Requests\Admin\ReferenceCustomer\UpdateReferenceCustomerRequest;
use App\Http\Resources\Admin\ReferenceCustomer\ReferenceCustomerCollection;
use App\Http\Resources\Admin\ReferenceCustomer\ReferenceCustomerResource;
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
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->referenceCustomerRepository = $referenceCustomerRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,reference_customers-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,reference_customers-create'])->only(['store']);
        $this->middleware(['ability:admin,reference_customers-update'])->only(['update']);
        $this->middleware(['ability:admin,reference_customers-delete'])->only(['destroy', 'forceDelete', 'restore', 'getOnlyTrashed']);
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
     * Store a newly created resource in storage.
     */
    public function store(StoreReferenceCustomerRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->referenceCustomerRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.referenceCustomers.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.referenceCustomers.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            //    return  $this->messageResponse( $e->getMessage());
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
            $data = $this->referenceCustomerRepository->findBySlug($slug);
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReferenceCustomerRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->referenceCustomerRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.referenceCustomers.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.referenceCustomers.updated-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            // return  $this->messageResponse( $e->getMessage());

            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->referenceCustomerRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.referenceCustomers.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.referenceCustomers.deleted-failed"),
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
