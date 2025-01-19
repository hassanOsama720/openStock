<?php

namespace App\Services\Auth;

use App\Enums\LoginType;
use App\Enums\OTPType;
use App\Models\Employee;
use App\Models\User;
use App\Traits\GeneralTrait;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthenticationService extends AuthBaseService
{
    use GeneralTrait, UploadTrait;

    private string $model;
    private $entity;

    public function __construct()
    {
        $this->model = User::class;
        parent::__construct(User::class);
    }


    /**
     * Registers a new user based on the provided data.
     * @param $request
     * @return array
     */
    public function register($request): array
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create a new entity
            $entity = $this->model::create($request);
            $this->entity = $entity;

            // define the identifier type
            $identifier_type = $this->getIdentifierType($request, true);

            // Send verification code to the target entity
            $entity->sendVerificationCode($identifier_type, OTPType::VERIFICATION);

            // Commit the transaction
            DB::commit();

            // Return success response with target entity details
            return [
                'key' => 'success',
                'msg' => __('auth.registered'),
                'user' => $entity
            ];
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            // Return error response
            return [
                'key' => 'fail',
                'msg' => $e->getMessage(),
                'user' => []
            ];
        }
    }

    /**
     * Complete the user profile based on the provided data.
     * @param $request
     * @return array
     */
    public function completeProfile($request): array
    {
        try {
            DB::beginTransaction();
            $this->entity = auth()->user();
            if ($this->entity->store) {
                return [
                    'key' => 'fail',
                    'msg' => __('auth.profile_already_completed'),
                ];
            }
            $store = $this->entity->store()->create([
                'name' => $request->get('name'),
                'commercial_register' => $request->get('commercial_register'),
                'currency' => $request->get('currency'),
            ]);
            $store->addresses()->createMany($request->get('addresses'));
            $store->addMediaFromRequest('logo')->toMediaCollection('logo');
            if ($request->hasFile('commercial_register_image')) {
                $store->addMediaFromRequest('commercial_register_image')->toMediaCollection('commercial_register_image');
            }

            DB::commit();

            return [
                'key' => 'success',
                'msg' => __('auth.profile_completed'),
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'key' => 'fail',
                'msg' => $e->getMessage(),
                'user' => []
            ];
        }
    }


    /**
     * Resend the verification code to the user based on the provided phone and country code or email.
     * @param $request
     * @return array
     */
    public function resendCode($request): array
    {
        // Find the entity
        $entity = $this->getEntity($request);

        // define the identifier type
        $identifier_type = $this->getIdentifierType($request);

        // Send the verification code to the user
        $entity->sendVerificationCode($identifier_type, OTPType::VERIFICATION);

        // Return the success message and the updated user data
        return [
            'key' => 'success',
            'msg' => __('auth.code_re_send'),
            'user' => $entity->refresh()
        ];
    }

    /**
     * Activate the user based on the provided data.
     * @param $request
     * @return array
     */
    public function activate($request): array
    {
        // Find the entity
        $entity = $this->getEntity($request);

        // activate the account
        $entity->markAsActive();

        // Return the response data
        return [
            'key' => 'success',
            'msg' => __('auth.activated'),
            'user' => $entity->refresh()
        ];
    }

    /**
     * Login the user based on the provided data.
     * @param $request
     * @return array
     */
    public function login($request): array
    {
        // Find the entity
        $entity = $this->getEntity($request);
        // define the identifier type
        $identifier_type = $this->getIdentifierType($request);

        $response = [
            'key' => 'success',
            'msg' => __('auth.signed'),
            'user' => $entity
        ];

        // If entity does not exist, return failure
        if (!$entity) {
            $response = [
                'key' => 'fail',
                'msg' => __('auth.incorrect_key_or_phone'),
                'user' => []
            ];
        } elseif ((isset($request['way']) && $request['way'] == LoginType::NORMAL) && !Hash::check($request['password'], $entity->password)) {
            // If password is incorrect, return failure
            $response = [
                'key' => 'fail',
                'msg' => __('auth.incorrect_pass'),
                'user' => []
            ];
        }

        if (isset($request['way']) && $request['way'] == LoginType::CODE && $response['key'] == 'success') {
            // If all checks pass, return verification code
            $entity->sendVerificationCode($identifier_type, OTPType::LOGIN);
            $response = [
                'key' => 'needVerification',
                'msg' => __('auth.phone_code_sent'),
                'user' => $entity
            ];
        }

        return $response;
    }


    /**
     * Verify the user's phone number to login.
     * @param $request
     * @return array
     */
    public function verifyLogin($request): array
    {
        // Find the entity
        $entity = $this->getEntity($request);

        // define the identifier type
        $identifier_type = $this->getIdentifierType($request);

        // check if the user has authOtp
        $success = $entity->checkOtp($request->code, OTPType::LOGIN, $identifier_type);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        }

        // delete the user's otp
        $entity->otps()->where(['type' => OTPType::LOGIN, 'code' => $request->code])->delete();

        // Return the response data
        return [
            'key' => 'success',
            'msg' => __('apis.signed'),
            'user' => $entity
        ];
    }


    /**
     * Forget the user's password based on the provided data.
     * @param $request
     * @return array
     */
    public function forgetPasswordSendCode($request): array
    {
        // Find the entity
        $entity = $this->getEntity($request);

        if (!$entity) {
            return ['key' => 'fail', 'msg' => __('auth.incorrect_key_or_phone'), 'user' => []];
        }

        // define the identifier type
        $identifier_type = $this->getIdentifierType($request);

        // Send the verification code to the user
        $entity->sendVerificationCode($identifier_type, OTPType::FORGET_PASSWORD);

        return ['key' => 'success', 'msg' => __('apis.success'), 'user' => $entity->refresh()];
    }


    /**
     * Check the user's password reset code based on the provided data.
     * @param $request
     * @return array
     */
    public function forgetPasswordCheckCode($request): array
    {
        // Find the entity
        $entity = $this->getEntity($request);

        $response = ['key' => 'fail', 'msg' => __('auth.incorrect_key_or_phone'), 'user' => []];

        if ($entity) {
            // define the identifier type
            $identifier_type = $this->getIdentifierType($request);

            // check if the user has authOtp
            $success = $entity->checkOtp($request->code, OTPType::FORGET_PASSWORD, $identifier_type);
            if ($success == 'fail') {
                $response['msg'] = __('auth.code_invalid');
            } elseif ($success == 'expired') {
                $response['msg'] = __('auth.code_expired');
            } else {
                $response = ['key' => 'success', 'msg' => __('auth.code_checked'), 'user' => $entity->refresh()];
            }
        }

        return $response;
    }


    /**
     * Reset the user's password based on the provided data.
     * @param $request
     * @return array
     */
    public function resetPassword($request): array
    {
        // Find the entity
        $entity = $this->getEntity($request);

        try {

            // Start a database transaction
            DB::beginTransaction();
            // delete the user's otp
            $entity->otps()->where(['type' => OTPType::FORGET_PASSWORD, 'code' => $request->code])->delete();

            // UpdateProfile the user's password
            $entity->update(['password' => $request['password']]);

            // Commit the transaction
            DB::commit();
            // Return success message
            return [
                'key' => 'success',
                'msg' => __('auth.password_changed')
            ];
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            // Return failure message
            return [
                'key' => 'fail',
                'msg' => $e->getMessage()
            ];
        }
    }

    /**
     * Logout the user.
     * @return array
     */
    public function logout(): array
    {
        // Find the entity
        auth()->user()->logout();

        // Return success message
        return [
            'key' => 'success',
            'msg' => __('apis.loggedOut')
        ];
    }


    /**
     * Delete the user's account.
     * @return array
     */
    public function deleteAccount(): array
    {
        // Find the entity
        $entity = auth()->user();

        try {
            // Start a database transaction
            DB::beginTransaction();

            // delete the user's tokens
            $entity->tokens()->delete();

            // delete the user's account
            $entity->delete();

            // Commit the transaction
            DB::commit();

            // Return success message
            return [
                'key' => 'success',
                'msg' => __('auth.account_deleted')
            ];
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            // Return failure message
            return [
                'key' => 'fail',
                'msg' => $e->getMessage()
            ];
        }
    }
}
