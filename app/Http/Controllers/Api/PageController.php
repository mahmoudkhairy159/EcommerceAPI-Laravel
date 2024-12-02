<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Page\PageResource;
use App\Repositories\PageRepository;
use App\Traits\ApiResponseTrait;
use App\Traits\GeneralTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    use ApiResponseTrait;
    protected $pageRepository;
    protected $_config;
    protected $guard;
    public function __construct(PageRepository $pageRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->pageRepository = $pageRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);
    }

    public function show($slug)
    {
        try {
            $data = $this->pageRepository->findBySlug($slug);
            return $this->successResponse(new PageResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
