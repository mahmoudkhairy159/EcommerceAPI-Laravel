<?php

namespace App\Http\Requests\Admin\Vendor;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVendorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:vendors,email'],
            'phone' => ['nullable', 'string', 'unique:vendors,phone'],
            'password' => ['nullable', 'string', 'min:3', 'max:256', 'confirmed'],
            'image' => ['nullable', 'image', 'max:2048'], // Assuming image uploads
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'facebook_link' => ['nullable', 'url'],
            'instagram_link' => ['nullable', 'url'],
            'twitter_link' => ['nullable', 'url'],
            'is_featured' => ['required', 'in:1,0'],
            'serial' => [
                'required',
                'integer',
                'min:1'
            ],
            'status' => ['required', 'in:1,0'],
            'blocked' => ['required', 'in:1,0'],
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
