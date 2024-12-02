<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageSection\StorePageSectionRequest;
use App\Http\Requests\Admin\PageSection\UpdatePageSectionRequest;
use App\Http\Resources\Admin\PageSection\PageSectionCollection;
use App\Http\Resources\Admin\PageSection\PageSectionResource;
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
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->pageSectionRepository = $pageSectionRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,pages-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,pages-create'])->only(['store']);
        $this->middleware(['ability:admin,pages-update'])->only(['update']);
        $this->middleware(['ability:admin,pages-delete'])->only(['destroy', 'forceDelete', 'restore', 'getOnlyTrashed']);
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
            $data = $this->pageSectionRepository->getAllByPageId($page_id)->paginate();
            return $this->successResponse(new PageSectionCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageSectionRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->pageSectionRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.pageSections.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.pageSections.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            //    return  $this->messageResponse( $e->getMessage());
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->pageSectionRepository->findOrFail($id);
            return $this->successResponse(new PageSectionResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageSectionRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->pageSectionRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.pageSections.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.pageSections.updated-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
            // return  $this->messageResponse( $e->getMessage());

            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->pageSectionRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.pageSections.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.pageSections.deleted-failed"),
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
