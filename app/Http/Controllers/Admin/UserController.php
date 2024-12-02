<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\Admin\User\UserCollection;
use App\Http\Resources\Admin\User\UserResource;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $userRepository;
    protected $userProfileRepository;

    protected $_config;
    protected $guard;

    public function __construct(UserRepository $userRepository, UserProfileRepository $userProfileRepository)
    {

        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,admins-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,admins-create'])->only(['store']);
        $this->middleware(['ability:admin,admins-update'])->only(['update']);
        $this->middleware(['ability:admin,admins-delete'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->userRepository->getAll()->paginate();
            return $this->successResponse(new UserCollection($data));
        } catch (Exception $e) {

            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $userData = $request->validated();
            $userData = $request->only('name', 'phone', 'image', 'email', 'password', 'status', 'blocked', /*'country_id', 'city_id'*/);
            $userData['created_by'] = auth()->guard($this->guard)->id();
            $userData['password'] = Hash::make($userData['password']);
            $userProfileData = $request->only('mode', 'language', 'bio', 'gender', 'birth_date');
            $userCreated = $this->userRepository->createOne($userData, $userProfileData);
            if ($userCreated) {
                return $this->messageResponse(
                    __("app.users.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.users.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            // return  $this->messageResponse( $e->getMessage());

            return $this->errorResponse(
                [$e->getMessage()],
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
            $user = $this->userRepository->getOneByUserId($id);
            if (!$user) {
                abort(404);
            }
            $data = new UserResource($user);
            return $this->successResponse($data);
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
            $data = $this->userRepository->findBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new UserResource($data));
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
    public function update(UpdateUserRequest $request, $id)
    {
        try {

            $userData = $request->only('name', 'phone', 'email', 'image', 'password', 'status', 'blocked', /*'country_id', 'city_id'*/);

            $userData['updated_by'] = auth()->guard($this->guard)->id();
            if (!isset($userData['password']) || !$userData['password']) {
                unset($userData['password']);
            }
            $userProfileData = $request->only('mode', 'language', 'bio', 'gender', 'birth_date');
            $updated = $this->userRepository->updateOne($userData, $userProfileData, $id);

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

            $deleted = $this->userRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.users.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.users.deleted-failed"),
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
            $updated = $this->userRepository->changeStatus($id);
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
}
