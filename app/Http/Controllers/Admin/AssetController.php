<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetRequest;
use App\Http\Resources\Admin\Asset\AssetResource;
use App\Repositories\AssetRepository;
use App\Repositories\PageRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    use ApiResponseTrait;
    protected $assetRepository;
    protected $pageRepository;
    protected $_config;
    protected $guard;
    public function __construct(AssetRepository $assetRepository, PageRepository $pageRepository)
    {
        $this->assetRepository = $assetRepository;
        $this->pageRepository = $pageRepository;
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->assetRepository = $assetRepository;
        $this->pageRepository = $pageRepository;        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,assets-read'])->only(['index']);
        $this->middleware(['ability:admin,assets-update'])->only(['update']);
    }
    public function index($page_id)
    {
        try {
            $page = $this->pageRepository->findOrFail($page_id);
            $assets = $page->assets;
            return $this->successResponse(AssetResource::collection($assets));

        } catch (\Exception $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

    public function update(AssetRequest $request, string $asset_name)
    {
        try {
            $data = $request->validated();
            $updated = $this->assetRepository->updateOne($asset_name, $data);

            if ($updated) {
                return $this->messageResponse(
                    __('app.assets.updated-successfully'),
                    200
                );
            }{
                return $this->messageResponse(
                    __('app.assets.updated-failed'),
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
