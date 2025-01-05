<?php

namespace App\Http\Requests\Admin\HeroSlider;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateHeroSliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'heading' => ['nullable', 'string', 'max:255'],
            'paragraph' => ['nullable', 'string'],
            'serial' => ['required', 'numeric'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif'],
            'button_url' => ['nullable', 'url'],
            'status' => ['required', 'in:1,0'],
        ];
    }


    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json([
                'data' => $validator->errors(),
                'message' => __('global.validation_errors'),
                'status' => false,
            ], 400)
        );
    }
}
