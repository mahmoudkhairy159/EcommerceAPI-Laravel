<?php
namespace App\Http\Requests\Admin\Product;

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:products,code'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keys' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'offer_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'offer_start_date' => ['nullable', 'date', 'before_or_equal:offer_end_date'],
            'offer_end_date' => ['nullable', 'date', 'after_or_equal:offer_start_date'],
            'currency' => ['required', 'string', 'max:10'],
            'quantity' => ['required', 'integer', 'min:0'],
            'alert_stock_quantity' => ['nullable', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'long_description' => ['nullable', 'string'],
            'return_policy' => ['nullable', 'string'],
            'is_featured' =>  ['required', 'in:0,1' ],
            'is_top' => ['required', 'in:0,1' ],
            'is_best' =>['required', 'in:0,1' ],
            'approval_status' => ['required', 'in:0,1,2' ],
            'status' => ['required', 'in:0,1' ],
            'serial' => ['required', 'string', 'min:1'],
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
      /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'tax' => $this->input('price',0)*core()->getTaxPercentage(),
        ]);
    }
}
