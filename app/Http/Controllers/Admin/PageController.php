<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\Page\PageCollection;
use App\Http\Resources\Admin\Page\PageResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\UpdatePageRequest;
use App\Repositories\PageRepository;
use App\Traits\ApiResponseTrait;
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
        $this->pageRepository = $pageRepository;
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->pageRepository = $pageRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,pages-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,pages-update'])->only(['update']);
        $this->middleware(['ability:admin,pages-delete'])->only(['destroy']);
    }

    public function index()
    {
        try {
            $data = $this->pageRepository->getAll()->paginate();
            return $this->successResponse(new PageCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
                __('app.something-went-wrong'),
                500
            );
        }
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

    public function update(UpdatePageRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $updated = $this->pageRepository->update($data, $id);

            if ($updated) {
                return $this->messageResponse(
                    __('app.pages.updated-successfully'),
                    200
                );
            }{
                return $this->messageResponse(
                    __('app.pages.updated-failed'),
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

}
