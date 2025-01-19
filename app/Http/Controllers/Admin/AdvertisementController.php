<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Advertisement\StoreAdvertisementRequest;
use App\Http\Requests\Admin\Advertisement\UpdateAdvertisementRequest;
use App\Http\Requests\Admin\Serial\UpdateSerialRequest;
use App\Http\Resources\Admin\Advertisement\AdvertisementCollection;
use App\Http\Resources\Admin\Advertisement\AdvertisementResource;
use App\Repositories\AdvertisementRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    use ApiResponseTrait;
    protected $advertisementRepository;
    protected $_config;
    protected $guard;
    public function __construct(AdvertisementRepository $advertisementRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->advertisementRepository = $advertisementRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,advertisements-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,advertisements-create'])->only(['store']);
        $this->middleware(['ability:admin,advertisements-update'])->only(['update']);
        $this->middleware(['ability:admin,advertisements-delete'])->only(['destroy']);
    }
    /**Introduction
    Issues
    Changelog
    FAQ

     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->advertisementRepository->getAll()->paginate();
            return $this->successResponse(new AdvertisementCollection($data));
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
    public function store(StoreAdvertisementRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->advertisementRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.advertisements.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.advertisements.created-failed"),
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
            $data = $this->advertisementRepository->findOrFail($id);
            return $this->successResponse(new AdvertisementResource($data));
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
    public function update(UpdateAdvertisementRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->advertisementRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.advertisements.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.advertisements.updated-failed"),
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
    public function changeStatus($id)
    {

        try {

            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->advertisementRepository->changeStatus($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.advertisements.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.advertisements.updated-failed"),
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
            $deleted = $this->advertisementRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.advertisements.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.advertisements.deleted-failed"),
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
