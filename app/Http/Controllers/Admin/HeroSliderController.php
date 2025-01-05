<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroSlider\StoreHeroSliderRequest;
use App\Http\Requests\Admin\HeroSlider\UpdateHeroSliderRequest;
use App\Http\Resources\Admin\HeroSlider\HeroSliderCollection;
use App\Http\Resources\Admin\HeroSlider\HeroSliderResource;
use App\Repositories\HeroSliderRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class HeroSliderController extends Controller
{

    use ApiResponseTrait;
    protected $heroSliderRepository;
    protected $_config;
    protected $guard;
    public function __construct(HeroSliderRepository $heroSliderRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->heroSliderRepository = $heroSliderRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,hero_sliders-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,hero_sliders-create'])->only(['store']);
        $this->middleware(['ability:admin,hero_sliders-update'])->only(['update']);
        $this->middleware(['ability:admin,hero_sliders-delete'])->only(['destroy']);
    }


    public function index()
    {
        try {
            $data = $this->heroSliderRepository->getAll()->paginate();
            return $this->successResponse(new HeroSliderCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function store(StoreHeroSliderRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();

            $created = $this->heroSliderRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.heroSliders.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.heroSliders.created-failed"),
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


    public function show($id)
    {
        try {
            $data = $this->heroSliderRepository->findOrFail($id);
            return $this->successResponse(new HeroSliderResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function update(UpdateHeroSliderRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();
            $updated = $this->heroSliderRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.heroSliders.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.heroSliders.updated-failed"),
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
            $deleted = $this->heroSliderRepository->deleteOne($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.heroSliders.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.heroSliders.deleted-failed"),
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
