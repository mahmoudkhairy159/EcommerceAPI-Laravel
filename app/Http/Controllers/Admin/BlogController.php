<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\StoreBlogRequest;
use App\Http\Requests\Admin\Blog\UpdateBlogRequest;
use App\Http\Resources\Admin\Blog\BlogCollection;
use App\Http\Resources\Admin\Blog\BlogResource;
use App\Repositories\BlogRepository;
use Illuminate\Support\Facades\Auth;


class BlogController extends Controller
{
    use ApiResponseTrait;


    protected $blogRepository;

    protected $_config;
    protected $guard;

    public function __construct(BlogRepository $blogRepository)
    {


        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->blogRepository = $blogRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,permission:blogs.show'])->only(['index', 'show']);
        $this->middleware(['ability:admin,blogs.create'])->only(['store']);
        $this->middleware(['ability:admin,blogs.update'])->only(['update']);
        $this->middleware(['ability:admin,permission:blogs.destroy'])->only(['destroy', 'forceDelete', 'restore', 'getOnlyTrashed']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->blogRepository->getAll()->paginate();
            return $this->successResponse(new BlogCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
                __('app.something-went-wrong'),
                500
            );
        }
    }



    public function store(StoreBlogRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->guard($this->guard)->id();
            $created = $this->blogRepository->createOne($data);

            if ($created) {
                return $this->messageResponse(
                    __("app.blogs.created-successfully"),
                    true,
                    201
                );
            }{
                return $this->messageResponse(
                    __("app.blogs.created-failed"),
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
            $data = $this->blogRepository->getOneById($id)->findOrFail($id);
            return $this->successResponse(new BlogResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }


    public function update(UpdateBlogRequest $request, $id)
    {
        try {

            $data = $request->validated();
            $data['updated_by'] = auth()->guard($this->guard)->id();

            $updated = $this->blogRepository->updateOne($data, $id);
            if ($updated) {
                return $this->messageResponse(
                    __("app.blogs.updated-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.blogs.updated-failed"),
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

            $deleted = $this->blogRepository->delete($id);

            if ($deleted) {
                return $this->messageResponse(
                    __('blog::app.blogs.deleted-successfully'),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __('blog::app.blogs.deleted-failed'),
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
             $data = $this->blogRepository->getOnlyTrashed()->paginate();
             return $this->successResponse(new BlogCollection($data));
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
             $deleted = $this->blogRepository->forceDelete($id);
             if ($deleted) {
                 return $this->messageResponse(
                     __("blog::app.blogs.deleted-successfully"),
                     true,
                     200
                 );
             } {
                 return $this->messageResponse(
                     __("blog::app.blogs.deleted-failed"),
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
             $restored = $this->blogRepository->restore($id);
             if ($restored) {
                 return $this->messageResponse(
                     __("blog::app.blogs.restored-successfully"),
                     true,
                     200
                 );
             } {
                 return $this->messageResponse(
                     __("blog::app.blogs.restored-failed"),
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
