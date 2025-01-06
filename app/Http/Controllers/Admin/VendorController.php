<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Vendor\StoreVendorRequest;
use App\Http\Requests\Admin\Vendor\UpdateVendorRequest;
use App\Http\Resources\Admin\Vendor\VendorCollection;
use App\Http\Resources\Admin\Vendor\VendorResource;
use App\Repositories\VendorRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    use ApiResponseTrait;

    protected $vendorRepository;

    protected $_config;
    protected $guard;

    public function __construct(VendorRepository $vendorRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->vendorRepository = $vendorRepository;
        // abilitys
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,vendors-read'])->only(['index', 'show']);
        $this->middleware(['ability::admin,vendors-create'])->only(['store']);
        $this->middleware(['ability::admin,vendors-update'])->only(['update']);
        $this->middleware(['ability::admin,vendors-delete'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->vendorRepository->getAll()->paginate();
            return $this->successResponse(new VendorCollection($data));
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
    public function store(StoreVendorRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->vendorRepository->createOne($data);
            if ($created) {
                return $this->messageResponse(
                    __("app.vendors.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.vendors.created-failed"),
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

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->vendorRepository->findOrFail($id);
            return $this->successResponse(new VendorResource($data));
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
            $data = $this->vendorRepository->findBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new VendorResource($data));
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
    public function update(UpdateVendorRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            if (!isset($data['password']) || !$data['password']) {
                unset($data['password']);
            }
            $updated = $this->vendorRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.vendors.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.vendors.updated-failed"),
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

    public function changeStatus($id)
    {

        try {

            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->vendorRepository->changeStatus($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.users.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.users.updated-failed"),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->vendorRepository->deleteOne($id);

            if ($deleted) {
                return $this->messageResponse(
                    __("app.vendors.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.vendors.deleted-failed"),
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
