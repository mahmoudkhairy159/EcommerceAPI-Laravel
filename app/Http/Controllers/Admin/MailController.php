<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Mail\SendMailsRequest;
use App\Mail\SampleMail;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    use ApiResponseTrait;
    protected $_config;
    protected $guard;
    public function __construct()
    {
        $this->guard = 'admin-api';
        request()->merge(['token' => 'true']);
        Auth::setDefaultDriver($this->guard);
        $this->_config = request('_config');
        // permissions
        $this->middleware('auth:' . $this->guard);
        $this->middleware(['role:admin'])->only(['send']);

    }

    public function send(SendMailsRequest $request)
    {
        try {
            $data = $request->validated();
            foreach ($data['mails'] as $mail) {
                Mail::to($mail)->send(new SampleMail($data['msg'], $data['subject']));
            }
            return $this->returnSuccessMessage('Mails Is Sent Successfully');
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }

}
