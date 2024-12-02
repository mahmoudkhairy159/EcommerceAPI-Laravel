<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Permission\PermissionResource;
use App\Models\Permission;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    use ApiResponseTrait;
    protected $_config;
    protected $guard;

    public function __construct()
    {

        $this->guard = 'admin-api';

        request()->merge(['token' => 'true']);

        Auth::setDefaultDriver($this->guard);

        $this->middleware(
            ['auth:' . $this->guard]
        );

        $this->_config = request('_config');
    }

    public function index()
    {
        try {
            $data = PermissionResource::collection(Permission::get());
            return $this->successResponse($data);
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
}
