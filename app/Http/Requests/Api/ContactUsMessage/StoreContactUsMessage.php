<?php

namespace App\Http\Requests\Api\ContactUsMessage;

use App\Enums\Currency;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreContactUsMessage extends ApiRequest
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
            'phone'             => ['required', 'string'],
            'email'             => ['required', 'email'],
            'title'             => ['required', 'string'],
            'message'           => ['required', 'string'],
        ];
    }
}
