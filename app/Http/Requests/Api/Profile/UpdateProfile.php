<?php

namespace App\Http\Requests\Api\Profile;

use App\Enums\Currency;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateProfile extends ApiRequest
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
            'name'              => ['required', 'string'],
            'last_name'         => ['required', 'string'],
            'sub_domain'        => ['required', 'string', 'unique:users,sub_domain,' . auth()->id()],
            'currency'          => ['required', 'in:' . implode(',', Currency::toArray())],
            'email'             => ['required', 'email', 'unique:users,email,' . auth()->id()],
            'phone'             => ['required', 'string', 'unique:users,phone,' . auth()->id()],
        ];
    }
}
