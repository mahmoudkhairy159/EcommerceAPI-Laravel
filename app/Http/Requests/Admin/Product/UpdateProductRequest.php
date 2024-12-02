<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:255|unique:products,code,' . $this->route('product'),
            'video_url' => 'nullable|string',
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10000'],
            'rank' => 'required|integer|min:0',
            'status' => 'required|integer|between:0,1',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'currency' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|integer|min:0',
            'alert_stock_quantity' => 'integer|min:0',
            'order_type' => 'sometimes|required|string|max:255',
            'short_description' => 'sometimes|required|string',
            'long_description' => 'sometimes|required|string',
            'return_policy' => 'nullable|string',
            'rate' => 'integer|between:0,255',
            'category_id' => 'required|exists:categories,id|integer',
            'brand_id' => 'required|exists:brands,id|integer',
            'services' => 'nullable|array',
            'services.*' => 'integer|exists:services,id',
            'is_featured' => 'required|integer|in:0,1',
//related products
            'relatedProductIds' => ['nullable', 'array'],
            'relatedProductIds.*' => [
                'required',
                'exists:products,id',
                'distinct', // Ensures that the related products are not duplicates
            ],
//accessory products

            'productAccessoriesIds' => ['nullable', 'array'],
            'productAccessoriesIds.*' => [
                'required',
                'exists:products,id',
                'distinct', // Ensures that the related products are not duplicates
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
