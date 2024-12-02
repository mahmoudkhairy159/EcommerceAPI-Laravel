<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactMessage\StoreContactMessageRequest;
use App\Repositories\ContactMessageRepository;
use App\Traits\ApiResponseTrait;

class ContactMessageController extends Controller
{
    use ApiResponseTrait;

    protected $contactMessageRepository;

    public function __construct(ContactMessageRepository $contactMessageRepository)
    {
        $this->contactMessageRepository = $contactMessageRepository;
    }

    public function store(StoreContactMessageRequest $request)
    {
        try {
            $data = $request->validated();
            $created = $this->contactMessageRepository->create($data);
            if ($created) {
                return $this->messageResponse(
                    __('app.contactMessages.created-successfully'),
                    201
                );
            }{
                return $this->messageResponse(
                    __('app.contactMessages.created-failed'),
                    false,
                    400
                );
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getMessage());
        }
    }

}
