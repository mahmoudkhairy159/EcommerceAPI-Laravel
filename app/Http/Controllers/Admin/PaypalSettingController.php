<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaypalSetting\UpdatePaypalSettingRequest;
use App\Http\Resources\Admin\PaypalSetting\PaypalSettingResource;
use App\Repositories\PaypalSettingRepository;
use App\Traits\ApiResponseTrait;
use App\Types\CacheKeysType;
use Exception;
use Illuminate\Support\Facades\Auth;

class PaypalSettingController extends Controller
{
    use ApiResponseTrait;

    protected $_config;
    protected $guard;
    protected $paypalSettingRepository;

    public function __construct( PaypalSettingRepository $paypalSettingRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->paypalSettingRepository = $paypalSettingRepository;

        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,paypal_settings-read'])->only(['index']);
        $this->middleware(['ability:admin,paypal_settings-update'])->only(['update']);

    }
    public function index()
    {
        try {

            $data =core()->getPaypalSetting();
            // return $paypalSetting;
            return $this->successResponse(new PaypalSettingResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
       public function update(UpdatePaypalSettingRequest $request)
    {
        try {
            $data = $request->validated();

            $updated = $this->paypalSettingRepository->updateOne($data);
            $this->clearSettingsCache();
            if ($updated) {
                return $this->messageResponse(
                    __("app.paypalSetting.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.paypalSetting.updated-failed"),
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
        $this->deleteCache(CacheKeysType::PAYPAL_SETTING_CACHE);
    }
}
