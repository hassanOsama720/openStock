<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\Currency;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class Register extends ApiRequest
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
            'email'             => ['required', 'email', 'unique:users'],
            'password'          => ['required', 'min:8', 'confirmed'],
            'name'              => ['required', 'string', 'unique:users'],
            'last_name'         => ['required', 'string'],
            'phone'             => ['required', 'string', 'unique:users'],
            'sub_domain'        => ['required', 'string', 'unique:users'],
            'currency'          => ['required', 'in:' . implode(',', Currency::toArray())],
        ];
    }
}
