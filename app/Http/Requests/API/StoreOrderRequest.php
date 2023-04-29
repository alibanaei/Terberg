<?php

namespace App\Http\Requests\API;

use App\Enums\OrderTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Database\Query\Builder;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['required', new Enum(OrderTypeEnum::class)],
            'productIds' => [
                'array',
                Rule::requiredIf(fn () => $this->type == OrderTypeEnum::Product->value),
            ],
            'productIds.*' => [
                Rule::exists('products', 'id')->where(function (Builder $query) {
                    return $query->where('active', true);
                }),
            ],
            'serviceIds' => [
                'array',
                Rule::requiredIf(fn () => $this->type == OrderTypeEnum::Service->value)
            ],
            'serviceIds.*' => [
                Rule::exists('services', 'id')->where(function (Builder $query) {
                    return $query->where('active', true);
                }),
            ],
            'optionIds' => 'array',
            'optionIds.*' => [
                Rule::exists('options', 'id')->where(function (Builder $query) {
                    return $query->where('active', true);
                }),
            ],
        ];
    }
}
