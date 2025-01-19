<?php

namespace App\Http\Requests\Api\Profile;

use App\Enums\Currency;
use App\Enums\StoreActivityType;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateStore extends ApiRequest
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
            'new_addresses' => ['nullable', 'array'],
            'new_addresses.*.address' => ['required', 'string'],
            'new_addresses.*.name' => ['required', 'string'],
            'new_addresses.*.lat' => ['required', 'numeric'],
            'new_addresses.*.lng' => ['required', 'numeric'],
            'remove_addresses' => ['nullable', 'array'],
            'remove_addresses.*' => ['required', 'exists:addresses,id,store_id,' . auth()->user()?->store?->id],
            'commercial_register' => ['nullable', 'string'],
            'logo' => ['nullable', 'image'],
            'commercial_register_image' => ['nullable', 'image'],
            'currency' => ['required', 'in:' . implode(',', Currency::toArray())],
        ];
    }
}
