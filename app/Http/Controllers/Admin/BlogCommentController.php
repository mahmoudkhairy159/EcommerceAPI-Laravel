<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BlogComment\BlogCommentCollection;
use App\Http\Resources\Admin\BlogComment\BlogCommentResource;
use App\Repositories\BlogCommentRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    use ApiResponseTrait;


    protected $blogCommentRepository;

    protected $_config;
    protected $guard;

    public function __construct(BlogCommentRepository $blogCommentRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->blogCommentRepository = $blogCommentRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,permission:blog_comments.show'])->only(['index', 'show', 'getByBlogId', 'getByUserId']);
        $this->middleware(['ability:admin,permission:blog_comments.destroy'])->only(['destroy','forceDelete', 'restore', 'getOnlyTrashed']);
    }


    public function getByBlogId($blog_id)
    {
        try {
            $data = $this->blogCommentRepository->getByBlogId($blog_id)->paginate();
            return $this->successResponse(new BlogCommentCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByUserId($userId)
    {
        try {
            $data = $this->blogCommentRepository->getByUserId($userId)->paginate();
            return $this->successResponse(new BlogCommentCollection($data));
        } catch (Exception $e) {
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
            $data = $this->blogCommentRepository->findOrFail($id);
            return $this->successResponse(new BlogCommentResource($data));
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
            $blogComment = $this->blogCommentRepository->findOrFail($id);
            $deleted = $this->blogCommentRepository->delete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("blog::app.blogComments.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("blog::app.blogComments.deleted-failed"),
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
                $data = $this->blogCommentRepository->getOnlyTrashed()->paginate();
                return $this->successResponse(new BlogCommentCollection($data));
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
                $deleted = $this->blogCommentRepository->forceDelete($id);
                if ($deleted) {
                    return $this->messageResponse(
                        __("blog::app.blogComments.deleted-successfully"),
                        true,
                        200
                    );
                } {
                    return $this->messageResponse(
                        __("blog::app.blogComments.deleted-failed"),
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
                $restored = $this->blogCommentRepository->restore($id);
                if ($restored) {
                    return $this->messageResponse(
                        __("blog::app.blogComments.restored-successfully"),
                        true,
                        200
                    );
                } {
                    return $this->messageResponse(
                        __("blog::app.blogComments.restored-failed"),
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
}
