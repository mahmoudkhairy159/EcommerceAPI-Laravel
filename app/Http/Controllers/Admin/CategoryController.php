<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\BulkUpdateStatusRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Http\Requests\Admin\Serial\UpdateSerialRequest;
use App\Http\Resources\Admin\Category\CategoryCollection;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Repositories\CategoryRepository;
use App\Traits\ApiResponseTrait;
use App\Types\CacheKeysType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    protected $categoryRepository;
    protected $_config;
    protected $guard;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->categoryRepository = $categoryRepository;

        // Permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,categories-read'])->only(['index', 'show', 'getOnlyTrashed', 'getWithoutPagination']);
        $this->middleware(['ability:admin,categories-create'])->only(['store']);
        $this->middleware(['ability:admin,categories-update'])->only(['update', 'changeStatus', 'updateSerial']);
        $this->middleware(['ability:admin,categories-delete'])->only(['destroy', 'forceDelete', 'restore']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->categoryRepository->getAll()->paginate();
            return $this->successResponse( new CategoryCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Retrieve categories without pagination.
     */
    public function getWithoutPagination()
    {
        try {
            $data = $this->categoryRepository->getAll()->get();
            return $this->successResponse(CategoryResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }
    /**
     * Get child categories by parent ID.
     */
    public function getByParentId($parentId)
    {
        try {
            // Fetch child categories where parent_id matches the provided parentId
            $data = $this->categoryRepository->getByParentId($parentId)->get();

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
            $data = $this->categoryRepository->getMainCategories()->get();
            return $this->successResponse(CategoryResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }
    public function getTreeStructure()
    {
        try {
            // Retrieve all categories, and organize them in a tree structure
            $data = $this->categoryRepository->getTreeStructure();
            return $this->successResponse(CategoryResource::collection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();

            $created = $this->categoryRepository->createOne($data);
            $this->clearCategoriesCache();

            return $created
                ? $this->messageResponse(__('app.categories.created-successfully'), true, 201)
                : $this->messageResponse(__('app.categories.created-failed'), false, 400);
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->categoryRepository->findOrFail($id);
            return $this->successResponse(new CategoryResource($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Display a resource by slug.
     */
    public function showBySlug(string $slug)
    {
        try {
            $data = $this->categoryRepository->findBySlug($slug);
            if (!$data) {
                return $this->errorResponse([], __('app.data-not-found'), 404);
            }
            return $this->successResponse(new CategoryResource($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();

            $updated = $this->categoryRepository->updateOne($data, $id);
            $this->clearCategoriesCache();

            return $updated
                ? $this->messageResponse(__('app.categories.updated-successfully'), true, 200)
                : $this->messageResponse(__('app.categories.updated-failed'), false, 400);
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Change the status of the specified resource.
     */
    public function changeStatus($id)
    {
        try {
            $data['updated_by'] = auth()->guard($this->guard)->id();

            $updated = $this->categoryRepository->changeStatus($id);
            $this->clearCategoriesCache();

            return $updated
                ? $this->messageResponse(__('app.categories.status-updated-successfully'), true, 200)
                : $this->messageResponse(__('app.categories.status-update-failed'), false, 400);
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Update the serial rank of the resource.
     */
    public function updateSerial(UpdateSerialRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();

            $updated = $this->categoryRepository->updateSerial($data, $id);
            $this->clearCategoriesCache();

            return $updated
                ? $this->messageResponse(__('app.categories.serial-updated-successfully'), true, 200)
                : $this->messageResponse(__('app.categories.serial-update-failed'), false, 400);
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }
    /**
     * Bulk update the status of multiple categories.
     */
    public function bulkUpdateStatus(BulkUpdateStatusRequest $request)
    {
        try {
            // Validate the request data
            $data = $request->validated();

            // Call the bulk update method from the repository
            $updated = $this->categoryRepository->bulkUpdateStatus($data['ids'], $data['status']);

            // Check if the update was successful
            if ($updated) {
                $this->clearCategoriesCache();
                return $this->messageResponse(__('app.categories.updated-successfully'), true, 200);
            } else {
                return $this->messageResponse(__('app.categories.update-failed'), false, 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->categoryRepository->deleteOne($id);
            $this->clearCategoriesCache();

            return $deleted
                ? $this->messageResponse(__('app.categories.deleted-successfully'), true, 200)
                : $this->messageResponse(__('app.categories.deleted-failed'), false, 400);
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Get only trashed resources.
     */
    public function getOnlyTrashed()
    {
        try {
            $data = $this->categoryRepository->getOnlyTrashed()->paginate();
            return $this->successResponse(new CategoryCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Permanently delete a resource.
     */
    public function forceDelete($id)
    {
        try {
            $deleted = $this->categoryRepository->forceDelete($id);
            $this->clearCategoriesCache();

            return $deleted
                ? $this->messageResponse(__('app.categories.deleted-successfully'), true, 200)
                : $this->messageResponse(__('app.categories.deleted-failed'), false, 400);
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }

    /**
     * Restore a trashed resource.
     */
    public function restore($id)
    {
        try {
            $restored = $this->categoryRepository->restore($id);
            $this->clearCategoriesCache();
            return $restored
                ? $this->messageResponse(__('app.categories.restored-successfully'), true, 200)
                : $this->messageResponse(__('app.categories.restored-failed'), false, 400);
        } catch (Exception $e) {
            return $this->errorResponse([], __('app.something-went-wrong'), 500);
        }
    }
    private function clearCategoriesCache()
    {
        $this->deleteCache(CacheKeysType::CATEGORIES_TREE_CACHE);
    }
}
