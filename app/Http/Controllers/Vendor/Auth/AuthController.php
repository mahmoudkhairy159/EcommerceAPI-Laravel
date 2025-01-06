<?php
namespace App\Http\Controllers\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Auth\VendorLoginRequest;
use App\Http\Requests\Vendor\Auth\AuthUpdateVendorRequest;
use App\Http\Resources\Vendor\Vendor\VendorResource;
use App\Repositories\VendorRepository;
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
    protected $vendorRepository;
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;


    public function __construct(VendorRepository $vendorRepository)
    {
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->vendorRepository = $vendorRepository;
        $this->middleware('auth:' . $this->guard)->except('login');
    }



    public function login(VendorLoginRequest $request)
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

            $vendor = auth($this->guard)->user();
            if (!$vendor->status || $vendor->blocked) {
                $message = $vendor->blocked ? "Your Account Has Been Blocked" : "Your Account Is Inactive";
                auth()->guard($this->guard)->logout();
                return $this->errorResponse(
                    [],
                    $message,
                    400
                );
            } else

                $data = [
                    'vendor' => new VendorResource($vendor),
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
            $vendor = auth($this->guard)->user();
            return $this->successResponse(
                new VendorResource($vendor),
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



    public function update(AuthUpdateVendorRequest $request)
    {
        try {
            $vendor = auth(guard: $this->guard)->user();
            $data = $request->only(
                'name',
                'phone',
                'email',
                'image',
                'password',
                'description',
                'address',
                'facebook_link',
                'instagram_link',
                'twitter_link'
            );
            if (!isset($data['password']) || !$data['password']) {
                unset($data['password']);
            }
            $updatedVendor = $this->vendorRepository->updateOne($data, $vendor->id);
            return $this->successResponse(
                new VendorResource($updatedVendor),
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
