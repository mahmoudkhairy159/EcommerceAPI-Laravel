<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:256'],
            'email' => ['required', 'email', 'unique:users,email,' . $this->route('user')],
            'phone' => ['required', 'alpha_num', 'between:11,13', 'unique:users,phone,' . $this->route('user')],
            'address' => ['nullable', 'string', 'min:3', 'max:256'],
            'status' => ['required', 'in:1,0'],
            'blocked' => ['required', 'in:1,0'],
            // 'country_id' => ['nullable', 'exists:countries,id'],
            // 'city_id' => ['nullable', 'exists:cities,id'],
            'password' => ['nullable', 'string', 'min:3', 'max:256', 'confirmed'],
            //userProfile
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10000'],
            'bio' => ['nullable', 'string', 'min:3', 'max:256'],
            'language' => ['nullable', Rule::in(['en', 'ar', 'sv'])],
            'mode' => ['nullable', Rule::in(['dark', 'light', 'device_mode'])],
            'sound_effects' => ['nullable', Rule::in(['on', 'off'])],
            'gender' => ['nullable', 'string', Rule::in(['Male', 'Female', 'Non-binary', 'Prefer not to say'])],
            'birth_date' => ['nullable', 'date'],
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
