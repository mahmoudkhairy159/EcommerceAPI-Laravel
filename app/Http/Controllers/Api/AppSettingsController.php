<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AppSetting\AppSettingResource;
use App\Traits\ApiResponseTrait;
use App\Types\CacheKeysType;
use Exception;

class AppSettingsController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            $data = app(CacheKeysType::APP_SETTINGS_CACHE);
            return $this->successResponse(new AppSettingResource($data));
        } catch (Exception $e) {

            return $this->errorResponse(
                [$e->getMessage()],
                __('app.something-went-wrong'),
                500
            );
        }
    }
   
}
