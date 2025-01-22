<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Blog\BlogCollection;
use App\Http\Resources\Api\Blog\BlogResource;
use App\Repositories\BlogRepository;
use App\Types\CacheKeysType;
use Illuminate\Support\Facades\Auth;


class BlogController extends Controller
{
    use ApiResponseTrait;


    protected $blogRepository;
    protected $blogLikeRepository;

    protected $_config;
    protected $guard;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->blogRepository = $blogRepository;
        // permissions
        $this->middleware('auth:' . $this->guard)->except([
            'index',
            'show',
            'showBySlug',
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (!auth()->guard($this->guard)->check()) {
                request()->merge(['page' => 1]);
            }
            $data = $this->blogRepository->getAllActive()->paginate();
            return $this->successResponse(new BlogCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [$e->getMessage(), $e->getCode()],
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
            $data = $this->blogRepository->getActiveOneById($id);
            if (!$data) {
                return $this->messageResponse(
                    __('app.data-not-found'),
                    false,
                    404
                );
            }
            return $this->successResponse(new BlogResource($data));
        } catch (Exception $e) {
            dd($e->getMessage());
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function showBySlug($slug)
    {
        try {
            $data = $this->blogRepository->getActiveOneBySlug($slug);
            if (!$data) {
                return $this->messageResponse(
                    __('app.data-not-found'),
                    false,
                    404
                );
            }
            return $this->successResponse(new BlogResource($data));
        } catch (Exception $e) {
            dd($e->getMessage());
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }




}
