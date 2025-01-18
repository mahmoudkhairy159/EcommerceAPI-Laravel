<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StripeSetting\UpdateStripeSettingRequest;
use App\Http\Resources\Admin\StripeSetting\StripeSettingResource;
use App\Repositories\StripeSettingRepository;
use App\Traits\ApiResponseTrait;
use App\Types\CacheKeysType;
use Exception;
use Illuminate\Support\Facades\Auth;

class StripeSettingController extends Controller
{
    use ApiResponseTrait;

    protected $_config;
    protected $guard;
    protected $stripeSettingRepository;

    public function __construct( StripeSettingRepository $stripeSettingRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->stripeSettingRepository = $stripeSettingRepository;

        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,stripe_settings-read'])->only(['index']);
        $this->middleware(['ability:admin,stripe_settings-update'])->only(['update']);

    }
    public function index()
    {
        try {

            $data =core()->getStripeSetting();
            // return $stripeSetting;
            return $this->successResponse(new StripeSettingResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
       public function update(UpdateStripeSettingRequest $request)
    {
        try {
            $data = $request->validated();

            $updated = $this->stripeSettingRepository->updateOne($data);
            $this->clearSettingsCache();
            if ($updated) {
                return $this->messageResponse(
                    __("app.stripeSetting.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.stripeSetting.updated-failed"),
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

    private function clearSettingsCache()
    {
        $this->deleteCache(CacheKeysType::STRIPE_SETTING_CACHE);
    }
}
