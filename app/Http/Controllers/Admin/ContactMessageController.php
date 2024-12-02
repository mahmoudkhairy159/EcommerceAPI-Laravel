<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ContactMessage\ContactMessageCollection;
use App\Http\Resources\Admin\ContactMessage\ContactMessageResource;
use App\Repositories\ContactMessageRepository;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    use ApiResponseTrait;

    protected $_config;
    protected $guard;
    protected $contactMessageRepository;

    public function __construct(ContactMessageRepository $contactMessageRepository)
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        $this->contactMessageRepository = $contactMessageRepository;
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['ability:admin,contact_messages-read'])->only(['index', 'show']);
        $this->middleware(['ability:admin,contact_messages-delete'])->only(['destroy']);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = $this->contactMessageRepository->paginated();
            return $this->successResponse(new ContactMessageCollection($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function show(string $id)
    {
        try {
            $data = $this->contactMessageRepository->getOneById($id);
            return $this->successResponse(new ContactMessageResource($data));
        } catch (Exception $e) {
            return $this->errorResponse(
                [],
                __('app.something-went-wrong'),
                500
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $deleted = $this->contactMessageRepository->delete($id);
            if ($deleted) {
                return $this->messageResponse(
                    __("app.contactMessages.deleted-successfully"),
                    true,
                    200
                );
            }{
                return $this->messageResponse(
                    __("app.contactMessages.deleted-failed"),
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
