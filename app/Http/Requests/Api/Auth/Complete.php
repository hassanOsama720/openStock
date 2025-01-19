<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\Currency;
use App\Enums\StoreActivityType;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class Complete extends ApiRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'addresses' => ['required', 'array'],
            'addresses.*.address' => ['required', 'string'],
            'addresses.*.name' => ['required', 'string'],
            'addresses.*.lat' => ['required', 'numeric'],
            'addresses.*.lng' => ['required', 'numeric'],
            'commercial_register' => ['nullable', 'string'],
            'logo' => ['required', 'image'],
            'commercial_register_image' => ['nullable', 'image'],
            'currency' => ['required', 'in:' . implode(',', Currency::toArray())],
        ];
    }
}
