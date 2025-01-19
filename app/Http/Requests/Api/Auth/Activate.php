<?php

namespace App\Http\Requests\Api\Auth;

use App\Enums\OTPType;
use App\Http\Requests\Api\ApiRequest;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class Activate extends ApiRequest
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
            'code' => 'required|string|exists:auth_otps,code',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = User::where('sub_domain', $this->sub_domain)->first();
            if ($user?->otps()->where('code', $this->code)->where('type', OTPType::VERIFICATION)->where('expired_at', '>', now())->doesntExist()) {
                $validator->errors()->add('code', __('auth.invalid_code'));
            }
        });
    }
}
