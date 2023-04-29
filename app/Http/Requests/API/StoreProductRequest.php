<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'description' => 'required|min:10',
            'price' => 'required|numeric|regex:/^\d*(\.\d+)?$/|min:0',
            'product_type_id' => 'required|exists:product_types,id'
        ];
    }
}
