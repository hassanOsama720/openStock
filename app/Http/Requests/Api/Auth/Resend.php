<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiRequest;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class Resend extends ApiRequest
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
           'sub_domain' => 'required|string|exists:users,sub_domain',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = User::where('sub_domain', $this->sub_domain)->first();
            if ($user?->email_verified_at) {
                $validator->errors()->add('sub_domain', __('auth.already_verified'));
            }
        });
    }
}
