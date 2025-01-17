<?php

namespace App\Http\Requests\Admin\PaypalSetting;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdatePaypalSettingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd(request()->all());
        return [
            'client_id' => 'required|string|max:255',
            'client_secret' => 'required|string|max:255',
            'app_id' => 'nullable|string|max:255',
            'mode' => 'required|in:sandbox,production',
            'currency' => 'required|string|size:3',
            'payment_action'=>'required|in:Sale,Order,Authorization',
            'notify_url' => 'required|string|max:255',
            'locale' => 'required|string|max:255',
            'validate_ssl' => 'required|boolean',
            'status' => 'required|in:0,1',
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
