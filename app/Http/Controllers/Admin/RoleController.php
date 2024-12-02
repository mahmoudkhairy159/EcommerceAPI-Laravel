<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Repositories\RoleRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    use ApiResponseTrait;

    protected $roleRepository;

    protected $_config;
    protected $guard;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->roleRepository = $roleRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,roles-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,roles-create'])->only(['store']);
        $this->middleware(['ability:admin,roles-update'])->only(['update']);
        $this->middleware(['ability:admin,roles-delete'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->roleRepository->with('permissions')->filter(request()->all())->get();
            return $this->successResponse(RoleResource::collection($data));
        } catch (Exception $e) {
            dd($e->getMessage());
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
    public function store(StoreRoleRequest $request)
    {
        try {
            $data = $request->validated();
            $created = $this->roleRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.roles.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.roles.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            dd($e->getMessage());
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
            $data = $this->roleRepository->with('permissions')->find($id);
            return $this->successResponse(new RoleResource($data));
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
    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $updated = $this->roleRepository->updateOne($data, $id);

            if ($updated) {
                return $this->messageResponse(
                    __("app.roles.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.roles.updated-failed"),
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
            $role = $this->roleRepository->find($id);
            if (!$role) {
                return abort(404);
            }
            $deleted = $this->roleRepository->delete($id);

            if ($deleted) {
                return $this->messageResponse(
                    __("app.roles.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.roles.deleted-failed"),
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
