<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BlogComment\StoreBlogCommentReplyRequest;
use App\Http\Requests\Api\BlogComment\StoreBlogCommentRequest;
use App\Http\Requests\Api\BlogComment\UpdateBlogCommentRequest;
use App\Http\Resources\Admin\BlogComment\BlogCommentCollection;
use App\Http\Resources\Api\BlogComment\BlogCommentResource;
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
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->blogCommentRepository = $blogCommentRepository;
        // permissions
        $this->middleware('auth:' . $this->guard)->except([
            'getByBlogId',
            'show'
        ]);
    }

    public function getByBlogId($blogId)
    {
        try {
            if (!auth()->guard($this->guard)->check()) {
                request()->merge(['page' => 1]);
            }
            $data = $this->blogCommentRepository->getByBlogId($blogId)->paginate();
            return $this->successResponse(new BlogCommentCollection($data));
        } catch (Exception $e) {
            dd($e->getMessage());
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
    public function store(StoreBlogCommentRequest $request)
    {
        try {
            $data =  $request->validated();
            $data['user_id'] = auth()->guard($this->guard)->id();
            $created = $this->blogCommentRepository->create($data);

            if ($created) {
                return $this->successResponse(
                    new BlogCommentResource($created),
                    __("blog.blogComments.created-successfully"),
                    201
                );
            } {
                return $this->messageResponse(
                    __("blog.blogComments.created-failed"),
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
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogCommentRequest $request, $id)
    {
        try {
            $data['user_id'] = auth()->guard($this->guard)->id();
            $blogComment = $this->blogCommentRepository->where('user_id', $data['user_id'])->find($id);
            if (!$blogComment) {
                return abort(404);
            }
            $data =  $request->validated();
            $updated = $this->blogCommentRepository->update($data, $id);

            if ($updated) {
                return $this->successResponse(
                    new BlogCommentResource($updated),
                    __("blog.blogComments.updated-successfully"),
                    200
                );
            } {
                return $this->messageResponse(
                    __("blog.blogComments.updated-failed"),
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
            $data['user_id'] = auth()->guard($this->guard)->id();
            $blogComment = $this->blogCommentRepository->where('user_id', $data['user_id'])->findOrFail($id);
            $deleted = $this->blogCommentRepository->delete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("blog.blogComments.deleted-successfully"),
                    true,
                    200
                );
            } {
                return $this->messageResponse(
                    __("blog.blogComments.deleted-failed"),
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
    //////////////////////////////////comment_replies/////////////////////////////////////////////////////////
    public function reply(StoreBlogCommentReplyRequest $request, $commentId)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $replied = $this->blogCommentRepository->reply($data, $commentId);
            if ($replied) {
                return $this->successResponse(
                    new BlogCommentResource($replied),
                    __("blog.blogComments.created-successfully"),
                    201
                );
            } {
                return $this->messageResponse(
                    __("blog.blogComments.created-failed"),
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
    public function getRepliesByCommentId($commentId)
    {
        try {
            if (!auth()->guard($this->guard)->check()) {
                request()->merge(['page' => 1]);
            }
            $data = $this->blogCommentRepository->getRepliesByCommentId($commentId)->paginate();
            return $this->successResponse(new BlogCommentCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    //////////////////////////////////comment_replies/////////////////////////////////////////////////////////

}
