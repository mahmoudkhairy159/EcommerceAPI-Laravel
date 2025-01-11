<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShippingRule\StoreShippingRuleRequest;
use App\Http\Requests\Admin\ShippingRule\UpdateShippingRuleRequest;
use App\Http\Requests\Admin\Serial\UpdateSerialRequest;
use App\Http\Resources\Admin\ShippingRule\ShippingRuleCollection;
use App\Http\Resources\Admin\ShippingRule\ShippingRuleResource;
use App\Repositories\ShippingRuleRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ShippingRuleController extends Controller
{
    use ApiResponseTrait;
    protected $shippingRuleRepository;
    protected $_config;
    protected $guard;
    public function __construct(ShippingRuleRepository $shippingRuleRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->shippingRuleRepository = $shippingRuleRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,shipping_rules-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,shipping_rules-create'])->only(['store']);
        $this->middleware(['ability:admin,shipping_rules-update'])->only(['update']);
        $this->middleware(['ability:admin,shipping_rules-delete'])->only(['destroy']);
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
            $data = $this->shippingRuleRepository->getAll()->paginate();
            return $this->successResponse(new ShippingRuleCollection($data));
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
    public function store(StoreShippingRuleRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->shippingRuleRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.shippingRules.created-successfully"),
                    true,
                    201
                );
            } {
                return $this->messageResponse(
                    __("app.shippingRules.created-failed"),
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
            $data = $this->shippingRuleRepository->findOrFail($id);
            return $this->successResponse(new ShippingRuleResource($data));
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
            $data = $this->shippingRuleRepository->findBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new ShippingRuleResource($data));
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
    public function update(UpdateShippingRuleRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->shippingRuleRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.shippingRules.updated-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.shippingRules.updated-failed"),
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
            $updated = $this->shippingRuleRepository->changeStatus($id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.shippingRules.updated-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.shippingRules.updated-failed"),
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
            $deleted = $this->shippingRuleRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.shippingRules.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("app.shippingRules.deleted-failed"),
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
