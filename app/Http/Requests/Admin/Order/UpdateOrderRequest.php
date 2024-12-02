<?php

namespace App\Http\Requests\Admin\Order;

use App\Models\Order;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', Rule::in(Order::getPaymentMethods())],
            'order_type' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
            'state' => ['required', 'string'],
            'city' => ['required', 'string'],
            'pin_code' => ['required', 'string'],
            'order_phone_number' => ['required', 'alpha_num'],
            'notes' => ['nullable', 'string'],
            'products' => ['nullable', 'array'],
            'products.*.product_id' => ['required_with:products', 'exists:products,id'],
            'products.*.quantity' => ['required_with:products', 'integer', 'min:1'],
            'products.*.selling_price' => ['required_with:products', 'numeric'],
            'products.*.cost_price' => ['required_with:products', 'numeric'],
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
