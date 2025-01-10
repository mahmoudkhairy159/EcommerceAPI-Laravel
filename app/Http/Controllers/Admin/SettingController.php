<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateSettingsRequest;
use App\Http\Resources\Admin\AppSetting\AppSettingResource;
use App\Repositories\SettingsRepository;
use App\Traits\ApiResponseTrait;
use App\Types\CacheKeysType;
use Exception;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    use ApiResponseTrait;

    protected $_config;
    protected $guard;
    protected $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->settingsRepository = $settingsRepository;

        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,settings-read'])->only(['index','getCurrencyList','getTimezoneList']);
        $this->middleware(['ability:admin,settings-update'])->only(['update']);

    }
    public function index()
    {
        try {

            $data = $this->settingsRepository->getSettings();
            // return $settings;
            return $this->successResponse(new AppSettingResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getCurrencyList()
    {
        try {
            $data = config('settings.currency_list');
            // return $settings;
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getTimezoneList()
    {
        try {
            $data = config('settings.timezone_list');
            // return $settings;
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function update(UpdateSettingsRequest $request)
    {
        try {
            $data = $request->validated();

            $updated = $this->settingsRepository->updateOne($data);
            $this->clearSettingsCache();
            if ($updated) {
                return $this->messageResponse(
                    __("app.settings.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.settings.updated-failed"),
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
        $this->deleteCache(CacheKeysType::APP_SETTINGS_CACHE);
    }
}
