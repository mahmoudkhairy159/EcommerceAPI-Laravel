<?php
namespace App\Http\Requests\Admin\UserAddress;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUserAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'country_id' => [
                'nullable',
                'exists:countries,id',
            ],
            'state_id' => [
                'nullable',
                'exists:states,id',
            ],
            'city_id' => [
                'nullable',
                'exists:cities,id',
            ],
            'zip_code' => [
                'nullable',
                'string',
                'max:10', // Adjust the max length based on your requirements
            ],
            'address' => [
                'required',
                'string',
                'max:500', // Adjust the max length based on your requirements
            ],
        ];

    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => 'Validation Error',
            'statusCode' => 422,
        ], 422));
    }
}
