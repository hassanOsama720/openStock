<?php

namespace App\Http\Requests\Api\Profile;

use App\Enums\Currency;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdatePassword extends ApiRequest
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
            'old_password' => ['required', 'string'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }
}
