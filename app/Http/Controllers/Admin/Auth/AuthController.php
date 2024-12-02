<?php
namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Http\Requests\Admin\Auth\AuthUpdateAdminRequest;
use App\Http\Resources\Admin\Admin\AdminResource;
use App\Repositories\AdminRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponseTrait;
    /**
     * Contains current guard
     *
     * @var string
     */
    protected $guard;
    protected $adminRepository;
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;


    public function __construct(AdminRepository $adminRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->adminRepository = $adminRepository;
        $this->middleware('auth:' . $this->guard)->except('login');
    }



    public function login(AdminLoginRequest $request)
    {
        try {
            $request->validated();
            if (!$token = auth()->guard($this->guard)->attempt($request->only(['email', 'password']))) {
                return $this->errorResponse(
                    [],
                    "Invalid email or Password",
                    401
                );
            }

            $admin = auth($this->guard)->user();
            if (!$admin->status || $admin->blocked) {
                $message = $admin->blocked ? "Your Account Has Been Blocked" : "Your Account Is Inactive";
                auth()->guard($this->guard)->logout();
                return $this->errorResponse(
                    [],
                    $message,
                    400
                );
            } else

                $data = [
                    'admin' => new AdminResource($admin),
                    'token'   => $token,
                    'expires_in_minutes' =>Auth::factory()->getTTL()
                ];

            return $this->successResponse(
                $data,
                "Logged in successfully.",
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }



    public function get()
    {
        try {
            $admin = auth($this->guard)->user();
            return $this->successResponse(
                new AdminResource($admin),
                "Logged in successfully.",
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }



    public function update(AuthUpdateAdminRequest $request)
    {
        try {
            $admin = auth($this->guard)->user();
            $request->validated();
            $data = $request->only('name', 'phone', 'email', 'password');

            if (!isset($data['password']) || !$data['password']) {
                unset($data['password']);
            }
            $updatedAdmin = $this->adminRepository->update($data, $admin->id);
            return $this->successResponse(
                new AdminResource($updatedAdmin),
                "Data updated successfully",
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }


    public function logout()
    {
        try {
            auth()->guard($this->guard)->logout();
            return $this->messageResponse(
                "Logged out successfully.",
                true,
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }



    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {

            $data = [
                'access_token' => Auth::guard($this->guard)->refresh(),
                'expires_in_minutes' => Auth::factory()->getTTL(),
            ];
            return $this->successResponse(
                $data,
                "",
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
