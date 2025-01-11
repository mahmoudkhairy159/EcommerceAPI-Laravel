<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\StoreCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Http\Requests\Admin\Serial\UpdateSerialRequest;
use App\Http\Resources\Admin\Coupon\CouponCollection;
use App\Http\Resources\Admin\Coupon\CouponResource;
use App\Repositories\CouponRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    use ApiResponseTrait;
    protected $couponRepository;
    protected $_config;
    protected $guard;
    public function __construct(CouponRepository $couponRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->couponRepository = $couponRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,coupons-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,coupons-create'])->only(['store']);
        $this->middleware(['ability:admin,coupons-update'])->only(['update']);
        $this->middleware(['ability:admin,coupons-delete'])->only(['destroy']);
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
            $data = $this->couponRepository->getAll()->paginate();
            return $this->successResponse(new CouponCollection($data));
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
    public function store(StoreCouponRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->couponRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.coupons.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.coupons.created-failed"),
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
            $data = $this->couponRepository->findOrFail($id);
            return $this->successResponse(new CouponResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function showBySlug(string $slug)
    {
        try {
            $data = $this->couponRepository->findBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new CouponResource($data));
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
    public function update(UpdateCouponRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->couponRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.coupons.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.coupons.updated-failed"),
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
            $updated = $this->couponRepository->changeStatus($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.coupons.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.coupons.updated-failed"),
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
            $deleted = $this->couponRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.coupons.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.coupons.deleted-failed"),
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
