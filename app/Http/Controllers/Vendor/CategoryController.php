<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\Category\CategoryCollection;
use App\Http\Resources\Vendor\Category\CategoryResource;
use App\Repositories\CategoryRepository;
use App\Traits\ApiResponseTrait;
use App\Types\CacheKeysType;
use Exception;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use ApiResponseTrait;
    protected $categoryRepository;
    protected $_config;
    protected $guard;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->guard = 'vendor-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->categoryRepository = $categoryRepository;
        // permissions
        // $this->middleware('auth:' . $this->guard);

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
            $data = $this->categoryRepository->getAllActive()->paginate();
            return $this->successResponse(new CategoryCollection($data));
                } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getWithoutPagination()
    {
        try {
            $data = $this->categoryRepository->getAllActive()->get();
            return $this->successResponse(CategoryResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByParentId($parentId)
    {
        try {
            // Fetch child categories where parent_id matches the provided parentId
            $data = $this->categoryRepository->getActiveByParentId($parentId)->get();

            // Return success response with the fetched categories
            return $this->successResponse(CategoryResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }
    /**
     * Get the hierarchical structure of categories.
     */
    public function getMainCategories()
    {
        try {
            // Retrieve all categories, and organize them in a tree structure
            $data = $this->categoryRepository->getActiveMainCategories()->get();
            return $this->successResponse(CategoryResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }
    public function getTreeStructure()
    {
        try {
            // Retrieve all categories, and organize them in a tree structure
            // $data = $this->categoryRepository->getActiveTreeStructure();
            $data = app(CacheKeysType::CATEGORIES_TREE_CACHE);

            return $this->successResponse(CategoryResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->categoryRepository->getActiveOneById($id);
            return $this->successResponse(new CategoryResource($data));
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
            $data = $this->categoryRepository->findActiveBySlug($slug);
            if (!$data) {
                return $this->errorResponse(
                    [],
                    __('app.data-not-found'),
                    404
                );
            }
            return $this->successResponse(new CategoryResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

}
