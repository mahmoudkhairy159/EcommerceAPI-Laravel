<?php
namespace App\Http\Requests\Admin\Service;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
{
    return [
        'name' => [
            'required',    // Name is required
            'string',      // Ensure name is a string
            'max:255',     // Maximum length of 255 characters
        ],
        'description' => [
            'nullable',    // Description is optional
            'string',      // Ensure description is a string
            'max:1000',    // Maximum length of 1000 characters
        ],
        'category_id' => [
            'nullable',    // Category is optional
            'exists:categories,id', // Ensure the category_id exists in the categories table
        ],
        'code' => [
            'nullable',    // Code is optional
            'unique:services,code', // Ensure the code is unique in the brands table
        ],
        'image' => [
            'nullable',    // Image is optional
            'image',       // Ensure the file is an image
            'mimes:jpeg,png,jpg,gif', // Allowed image formats
            'max:10000',   // Max file size of 10MB
        ],
        'serial' => [
            'required',    // Serial is required
            'integer',     // Ensure serial is an integer
            'min:0',       // Minimum value for serial is 0
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
