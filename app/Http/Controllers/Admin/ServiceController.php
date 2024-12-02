<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Rank\UpdateRankRequest;
use App\Http\Requests\Admin\Service\StoreServiceRequest;
use App\Http\Requests\Admin\Service\UpdateServiceRequest;
use App\Http\Resources\Admin\Service\ServiceCollection;
use App\Http\Resources\Admin\Service\ServiceResource;
use App\Repositories\ServiceRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    use ApiResponseTrait;
    protected $serviceRepository;
    protected $_config;
    protected $guard;
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->serviceRepository = $serviceRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,services-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,services-create'])->only(['store']);
        $this->middleware(['ability:admin,services-update'])->only(['update']);
        $this->middleware(['ability:admin,services-delete'])->only(['destroy', 'forceDelete', 'restore', 'getOnlyTrashed']);
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
            $data = $this->serviceRepository->getAll()->paginate();
            return $this->successResponse(new ServiceCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getWithoutPagination()
    {
        try {
            $data = $this->serviceRepository->getAll()->get();
            return $this->successResponse(ServiceResource::collection($data));
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
    public function store(StoreServiceRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->serviceRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.services.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.services.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            return $this->messageResponse($e->getMessage());
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
            $data = $this->serviceRepository->findOrFail($id);
            return $this->successResponse(new ServiceResource($data));
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
            $data = $this->serviceRepository->findBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new ServiceResource($data));
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
    public function update(UpdateServiceRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->serviceRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.services.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.services.updated-failed"),
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
    public function changeStatus($id)
    {

        try {

            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->serviceRepository->changeStatus($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.services.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.services.updated-failed"),
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
    public function updateRank(UpdateRankRequest $request, $id)
    {

        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->serviceRepository->updateRank($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.services.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.services.updated-failed"),
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
            $deleted = $this->serviceRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.services.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.services.deleted-failed"),
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
    /***********Trashed model SoftDeletes**************/
    public function getOnlyTrashed()
    {
        try {
            $data = $this->serviceRepository->getOnlyTrashed()->paginate();
            return $this->successResponse(new ServiceCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function forceDelete($id)
    {
        try {
            $deleted = $this->serviceRepository->forceDelete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.services.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.services.deleted-failed"),
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

    public function restore($id)
    {
        try {
            $restored = $this->serviceRepository->restore($id);
            if ($restored) {
                return $this->messageResponse(
                    __("app.services.restored-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.services.restored-failed"),
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
    /***********Trashed model SoftDeletes**************/
}
