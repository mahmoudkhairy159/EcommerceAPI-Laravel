<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserRegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Models\User;
use App\Repositories\OtpRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTrait;
use App\Traits\UserOtpTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Requests\Api\Auth\SocialLoginRequest;
use App\Http\Requests\Api\Auth\UserSocialLoginRequest;
use App\Models\LinkedSocialAccount;
use Laravel\Socialite\Two\User as ProviderUser;

class SocialiteController extends Controller
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
    }

    /*  Full Flow
  The frontend calls Google’s OAuth service and retrieves the access_token.
  The frontend sends this token to your Laravel API in a request to the /login endpoint.
  The backend uses the access_token with Socialite to retrieve the user's data from Google.
  If the user is successfully authenticated, the backend generates a JWT token and returns it to the frontend, which can store it for subsequent requests.
  */
    //for Spa login

    public function login(UserSocialLoginRequest $request)
    {
        try {
            $accessToken = $request->get('access_token');
            $provider = $request->get('provider');

            $providerUser = Socialite::driver($provider)->userFromToken($accessToken);

            if ($providerUser) {
                $user = $this->findOrCreate($providerUser, $provider);
                $jwtToken = JWTAuth::fromUser($user);

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
            } else {
                return $this->errorResponse(
                    ['provider_user_error' => 'Unable to retrieve user from provider.'],
                    __('app.something-went-wrong'),
                    400
                );
            }

        } catch (Exception $exception) {
            return $this->errorResponse(
                [$exception->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    protected function findOrCreate(ProviderUser $providerUser, string $provider): User
    {
        try {
            DB::beginTransaction();

            // Check if the user is already linked with the social account
            $linkedSocialAccount = LinkedSocialAccount::where('provider_name', $provider)
                ->where('provider_id', $providerUser->getId())
                ->first();

            if ($linkedSocialAccount) {
                DB::commit();
                return $linkedSocialAccount->user;
            }

            // Try to find the user by email
            $user = null;
            if ($email = $providerUser->getEmail()) {
                $user = User::where('email', $email)->first();
            }

            // If user does not exist, create a new user
            if (!$user) {
                $user = User::create([
                    'name' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'image' => $providerUser->getAvatar(),
                    'password' => Str::random(24), // Use a random password for social login
                ]);
                $user->markEmailAsVerified();
            }

            // Link the social account to the user
            $user->linkedSocialAccounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            // Create a user profile
            $this->userProfileRepository->create(['user_id' => $user->id]);

            DB::commit();

            return $user;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e; // Re-throw the exception for the calling function to handle
        }
    }



    //for web login
    public function redirect(string $provider)
    {
        if (!in_array($provider, ['google', 'facebook', 'github'])) {
            return $this->errorResponse(
                ['Invalid provider'],
                __('app.auth.invalid_provider'),
                400
            );
        }
        $redirectUri = url("/api/user/auth/login/{$provider}/callback");
        return Socialite::driver($provider)->stateless()->redirectUrl('http://localhost/api/user/auth/login/google/callback')->redirect();
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     */
    protected function callback(string $provider)
    {
        try {
            if (!in_array($provider, ['google', 'facebook', 'github'])) {
                return $this->errorResponse(
                    ['Invalid provider'],
                    __('app.auth.invalid_provider'),
                    400
                );
            }
            DB::beginTransaction();

            $socialUser = Socialite::driver($provider)->stateless()->user();
            //to get access token
            // $accessToken = $socialUser->token;
            // dd($accessToken);
            //to get access token
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => Str::random(24),
                    'provider_id' => $socialUser->getId(),
                    'image' => $socialUser->getAvatar(),
                ]
            );
            $user->markEmailAsVerified();
            // Link the social account to the user
            $user->linkedSocialAccounts()->create([
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
            ]);

            $userProfile = $this->userProfileRepository->create(['user_id' => $user->id]);
            DB::commit();

            // Generate JWT token for the user
            $jwtToken = JWTAuth::fromUser($user);
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

}
