<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Http\Resources\Admin\Banner\BannerCollection;
use App\Http\Resources\Admin\Banner\BannerResource;
use App\Repositories\BannerRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{

    use ApiResponseTrait;
    protected $BannerRepository;
    protected $_config;
    protected $guard;
    public function __construct(BannerRepository $BannerRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->BannerRepository = $BannerRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,banners-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,banners-create'])->only(['store']);
        $this->middleware(['ability:admin,banners-update'])->only(['update']);
        $this->middleware(['ability:admin,banners-delete'])->only(['destroy']);
    }

  
    public function index()
    {
        try {
            $data = $this->BannerRepository->getAll()->paginate();
            return $this->successResponse(new BannerCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function store(StoreBannerRequest $request)
    {
        try {
            $data = $request->validated();
            $created = $this->BannerRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.banners.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.banners.created-failed"),
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = $this->BannerRepository->findOrFail($id);
            return $this->successResponse(new BannerResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function update(UpdateBannerRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $updated = $this->BannerRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.banners.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.banners.updated-failed"),
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

    public function destroy($id)
    {
        try {
            $deleted = $this->BannerRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.banners.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.banners.deleted-failed"),
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
