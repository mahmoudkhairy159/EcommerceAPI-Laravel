<?php

namespace App\Http\Requests\Admin\Mail;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendMailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'mails' => 'required|array',
            'mails.*' => 'required',
            'msg' => 'required|string',
            'subject' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json([
                'data' => $validator->errors(),
                'message' => __('global.validation_errors'),
                'status' => false,
            ], 400)
        );
    }
}
