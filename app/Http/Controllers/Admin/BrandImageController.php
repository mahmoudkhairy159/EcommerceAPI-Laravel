<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandImage\StoreBrandImageRequest;
use App\Http\Requests\Admin\BrandImage\UpdateBrandImageRequest;
use App\Http\Resources\Admin\BrandImage\BrandImageCollection;
use App\Http\Resources\Admin\BrandImage\BrandImageResource;
use App\Repositories\BrandImageRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class BrandImageController extends Controller
{
    use ApiResponseTrait;
    protected $brandImageRepository;
    protected $_config;
    protected $guard;
    public function __construct(BrandImageRepository $brandImageRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->brandImageRepository = $brandImageRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,brands-read'])->only(['getByBrandId', 'show']);
        $this->middleware(['ability:admin,brands-create'])->only(['store']);
        $this->middleware(['ability:admin,brands-update'])->only(['update']);
        $this->middleware(['ability:admin,brands-delete'])->only(['destroy']);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function getByBrandId($brand_id)
    {
        try {
            $data = $this->brandImageRepository->getByBrandId($brand_id)->paginate();
            return $this->successResponse(new BrandImageCollection($data));
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
    public function store(StoreBrandImageRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->brandImageRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.brandImages.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.brandImages.created-failed"),
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

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->brandImageRepository->findOrFail($id);
            return $this->successResponse(new BrandImageResource($data));
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
    public function update(UpdateBrandImageRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->brandImageRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.brandImages.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.brandImages.updated-failed"),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->brandImageRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.brandImages.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.brandImages.deleted-failed"),
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
