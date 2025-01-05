<?php
namespace App\Http\Requests\Admin\Category;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        'description' => ['nullable', 'string', 'max:1000'],
        'code' => ['nullable', 'unique:categories,code'],
        'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10000'],
        'serial' => ['required', 'integer', 'min:0'],
        'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
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
