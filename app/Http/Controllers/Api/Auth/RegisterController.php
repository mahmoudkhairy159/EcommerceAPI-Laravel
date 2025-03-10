<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserRegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Repositories\OtpRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTrait;
use App\Traits\UserOtpTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{

    use ApiResponseTrait, UserOtpTrait;

    protected $userRepository;
    protected $otpRepository;
    protected $userProfileRepository;

    protected $_config;
    protected $guard;

    public function __construct(UserRepository $userRepository, UserProfileRepository $userProfileRepository, OtpRepository $otpRepository)
    {
        $this->guard = 'user-api';
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
        $this->otpRepository = $otpRepository;

        $this->middleware('auth:' . $this->guard)->only(['update', 'me']);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     */
    protected function create(UserRegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $user = $this->userRepository->create($data);
            $userProfile = $this->userProfileRepository->create(['user_id' => $user->id]);
            DB::commit();

            // Generate JWT token for the user
            $jwtToken = JWTAuth::fromUser($user);

            $this->sendOtpCode($user);

            $user = new UserResource($user);
            $data = [
                'user' => new UserResource($user),
                'token' => $jwtToken,
                'expires_in_minutes' => Auth::factory()->getTTL(),
            ];

            return $this->successResponse(
                $data,
                __('app.auth.register.success_register_message'),
                201
            );
        } catch (Exception $e) {
            // return  $this->messageResponse( $e->getMessage());
            DB::rollBack();

            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     */
    public function me()
    {
        try {
            $user = auth($this->guard)->user();
            return $this->successResponse(
                new UserResource($user),
                __('app.auth.login.logged_in_successfully'),
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
}
