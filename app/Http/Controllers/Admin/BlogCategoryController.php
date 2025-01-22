<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategory\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\BlogCategory\UpdateBlogCategoryRequest;
use App\Http\Resources\Admin\BlogCategory\BlogCategoryCollection;
use App\Http\Resources\Admin\BlogCategory\BlogCategoryResource;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Facades\Auth;


class BlogCategoryController extends Controller
{
    use ApiResponseTrait;


    protected $blogCategoryRepository;

    protected $_config;
    protected $guard;

    public function __construct(BlogCategoryRepository $blogCategoryRepository)
    {


        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->blogCategoryRepository = $blogCategoryRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,permission:blog_categories.show'])->only(['index', 'show']);
        $this->middleware(['ability:admin,blog_categories-create'])->only(['store']);
        $this->middleware(['ability:admin,blog_categories-update'])->only(['update']);
        $this->middleware(['ability:admin,permission:blog_categories.destroy'])->only(['destroy', 'forceDelete', 'restore', 'getOnlyTrashed']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->blogCategoryRepository->getAll()->paginate();
            return $this->successResponse(new BlogCategoryCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
                __('app.something-went-wrong'),
                500
            );
        }
    }



    public function store(StoreBlogCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->blogCategoryRepository->create($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.blogCategories.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.blogCategories.created-failed"),
                    false,
                    400
                );
            }
        } catch (Exception $e) {
               return  $this->messageResponse( $e->getMessage());
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
            $data = $this->blogCategoryRepository->getOneById($id)->findOrFail($id);
            return $this->successResponse(new BlogCategoryResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }


    public function update(UpdateBlogCategoryRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();

            $updated = $this->blogCategoryRepository->update($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.blogCategories.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.blogCategories.updated-failed"),
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

            $deleted = $this->blogCategoryRepository->delete($id);

            if ($deleted) {
                return $this->messageResponse(
                    __('blogCategory::app.blogCategories.deleted-successfully'),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __('blogCategory::app.blogCategories.deleted-failed'),
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



     /***********Trashed model SoftDeletes**************/
     public function getOnlyTrashed()
     {
         try {
             $data = $this->blogCategoryRepository->getOnlyTrashed()->paginate();
             return $this->successResponse(new BlogCategoryCollection($data));
         } catch (Exception $e) {
             return $this->errorResponse(
                 [],
                 __('app.something-went-wrong'),
                 500
             );
         }
     }

     public function forceDelete($id)
     {
         try {
             $deleted = $this->blogCategoryRepository->forceDelete($id);
             if ($deleted) {
                 return $this->messageResponse(
                     __("blogCategory::app.blogCategories.deleted-successfully"),
                     true,
                     200
                 );
             } {
                 return $this->messageResponse(
                     __("blogCategory::app.blogCategories.deleted-failed"),
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

     public function restore($id)
     {
         try {
             $restored = $this->blogCategoryRepository->restore($id);
             if ($restored) {
                 return $this->messageResponse(
                     __("blogCategory::app.blogCategories.restored-successfully"),
                     true,
                     200
                 );
             } {
                 return $this->messageResponse(
                     __("blogCategory::app.blogCategories.restored-failed"),
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
