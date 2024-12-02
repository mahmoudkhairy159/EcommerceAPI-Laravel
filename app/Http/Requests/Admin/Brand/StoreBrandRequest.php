<?php
namespace App\Http\Requests\Admin\Brand;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string|max:10000',
            'long_description_status' => ['nullable', 'in:0,1'],
            'brief' => 'nullable|string|max:10000',
            'category_id' => 'nullable|exists:categories,id',
            'code' => ['nullable', 'unique:brands,code'],
            'image1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10000'],
            'image2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10000'],
            'brand_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10000'],

            'rank' => 'required|integer|min:0',
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
