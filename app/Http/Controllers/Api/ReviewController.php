<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Review\StoreReviewRequest;
use App\Http\Requests\Api\Review\UpdateReviewRequest;
use App\Http\Resources\Api\Review\ReviewResource;
use App\Http\Resources\Api\Review\ReviewCollection;
use App\Repositories\ReviewRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use ApiResponseTrait;

    protected $reviewRepository;

    protected $_config;
    protected $guard;
    protected $per_page;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->guard = 'user-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->reviewRepository = $reviewRepository;
        $this->per_page = config('pagination.default');
        // permissions
        $this->middleware('auth:' . $this->guard)->except([
            'getByProductId',
            'getByUserId',
            'show',
        ]);
    }

    public function getByProductId($product_id)
    {
        try {
            $data = $this->reviewRepository->getByProductId($product_id)->paginate($this->per_page);
            return $this->successResponse(new ReviewCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByServiceId($service_id)
    {
        try {
            $data = $this->reviewRepository->getByServiceId($service_id)->paginate($this->per_page);
            return $this->successResponse(new ReviewCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }
    public function getByUserId($user_id)
    {
        try {
            $data = $this->reviewRepository->getByUserId($user_id)->paginate($this->per_page);
            return $this->successResponse(new ReviewCollection($data));
        } catch (Exception $e) {
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
    public function store(StoreReviewRequest $request, $reviewable_id)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->guard($this->guard)->id();
            if($data['type']=='product'){
                $data['reviewable_type'] = 'App\\Models\\Product'; // Use double backslashes for the namespace
            }elseif($data['type']=='service'){
                $data['reviewable_type'] = 'App\\Models\\Service'; // Use double backslashes for the namespace
            }
            $data['reviewable_id'] = $reviewable_id;
            unset($data['type']);

            $created = $this->reviewRepository->create($data);

            if ($created) {

                return $this->successResponse(
                    new ReviewResource($created),
                    __("app.reviews.created-successfully"),
                    201
                );

            }{
                return $this->messageResponse(
                    __("app.reviews.created-failed"),
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
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data = $this->reviewRepository->findOrFail($id);

            return $this->successResponse(new ReviewResource($data));
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
    public function update(UpdateReviewRequest $request, $id)
    {
        try {
            $data['user_id'] = auth()->guard($this->guard)->id();
            $review = $this->reviewRepository->where('user_id', $data['user_id'])->find($id);
            if (!$review) {
                return abort(404);
            }
            $data = $request->validated();
            $updated = $this->reviewRepository->update($data, $id);

            if ($updated) {
                return $this->messageResponse(
                    __("app.reviews.updated-successfully"),
                    true,
                    200
                );

            }{
                return $this->messageResponse(
                    __("app.reviews.updated-failed"),
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
            $review = $this->reviewRepository->where('user_id', $data['user_id'])->findOrFail($id);
            $deleted = $this->reviewRepository->delete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.reviews.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.reviews.deleted-successfully"),
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
