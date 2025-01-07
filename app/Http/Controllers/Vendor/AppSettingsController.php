<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\AppSetting\AppSettingResource;
use App\Traits\ApiResponseTrait;
use App\Types\CacheKeysType;

class AppSettingsController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            $data = app(CacheKeysType::APP_SETTINGS_CACHE);
            return $this->successResponse(new AppSettingResource($data));
        } catch (\Exception $e) {

            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
