<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PageSection\PageSectionCollection;
use App\Repositories\PageSectionRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class PageSectionController extends Controller
{
    use ApiResponseTrait;
    protected $pageSectionRepository;
    protected $_config;
    protected $guard;
    public function __construct(PageSectionRepository $pageSectionRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->pageSectionRepository = $pageSectionRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);

    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function index($page_id)
    {
        try {
            $data = $this->pageSectionRepository->getAllActiveByPageId($page_id)->paginate();
            return $this->successResponse(new PageSectionCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
