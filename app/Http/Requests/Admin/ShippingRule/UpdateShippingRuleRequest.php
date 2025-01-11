<?php

namespace App\Http\Requests\Admin\ShippingRule;

use App\Enums\DiscountTypeEnum;
use App\Enums\ShippingRuleTypeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateShippingRuleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'type' => [
                'required',
                'string',
                Rule::in(ShippingRuleTypeEnum::getConstants())
            ],
            'min_cost' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:type,' . ShippingRuleTypeEnum::MIN_COST,
            ],
            'cost' => [
                'nullable',
                'numeric',
                'min:0',
                'required_if:type,' . ShippingRuleTypeEnum::FLAT_COST,
            ],
            'status' => [
                'required',
                'in:0,1'
            ]
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
