<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BlogCategory\BlogCategoryCollection;
use App\Http\Resources\Api\BlogCategory\BlogCategoryResource;
use App\Repositories\BlogCategoryRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class BlogCategoryController extends Controller
{
    use ApiResponseTrait;
    protected $blogCategoryRepository;
    protected $_config;
    protected $guard;
    public function __construct(BlogCategoryRepository $blogCategoryRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->blogCategoryRepository = $blogCategoryRepository;
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
            $data = $this->blogCategoryRepository->getAll()->paginate();
            return $this->successResponse(new BlogCategoryCollection($data));
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
            $data = $this->blogCategoryRepository->findOrFail($id);
            return $this->successResponse(new BlogCategoryResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }


}
