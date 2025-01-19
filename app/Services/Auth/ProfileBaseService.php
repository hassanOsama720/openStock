<?php

namespace App\Services\Auth;

use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Models\Contact;
use App\Traits\GeneralTrait;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileBaseService
{
    use GeneralTrait, UploadTrait;

    /**
     * Get the user profile
     *
     * @return array
     */
    public function getProfile(): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        $data = $entity->getResource();

        // Return the entity data
        return ['key' => 'success', 'msg' => __('apis.success'), 'user' => $data];
    }


    /**
     * UpdateProfile the user profile
     *
     * @param $request
     * @return array
     */
    public function updateProfile($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // UpdateProfile the user's data
        $entity->update($request);

        // Return the updated user data
        return ['key' => 'success', 'msg' => __('auth.account_updated'), 'user' => $entity->refresh()->getResource()];
    }

    /**
     * Update the user profile based on the provided data.
     * @param $request
     * @return array
     */
    public function updateStore($request): array
    {
        try {
            DB::beginTransaction();
            $this->entity = auth()->user();
            $store = $this->entity->store;
            $store->update([
                'name' => $request->get('name'),
                'commercial_register' => $request->get('commercial_register'),
                'currency' => $request->get('currency'),
            ]);
            $store->addresses()->createMany($request->get('new_addresses'));
            if ($request->has('remove_addresses')){
                $store->addresses()->whereIn('id', $request->get('remove_addresses'))->delete();
            }
            if ($request->hasFile('logo')) {
                $store->clearMediaCollection('logo');
                $store->addMediaFromRequest('logo')->toMediaCollection('logo');
            }
            if ($request->hasFile('commercial_register_image')) {
                $store->clearMediaCollection('commercial_register_image');
                $store->addMediaFromRequest('commercial_register_image')->toMediaCollection('commercial_register_image');
            }

            DB::commit();

            return [
                'key' => 'success',
                'msg' => __('auth.profile_updated'),
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
     * UpdateProfile the user's password
     *
     * @param $request
     * @return array
     */
    public function updatePassword($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the current password is correct
        if (!Hash::check($request->old_password, $entity->password)) {
            return ['key' => 'fail', 'msg' => __('auth.incorrect_pass'), 'user' => []];
        }

        // UpdateProfile the user's password
        $entity->update(['password' => $request->password]);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.password_changed'), 'user' => $entity->refresh()->getResource()];
    }


    /**
     * Send a verification code to the user's phone number
     *
     * @param $request
     * @return array
     */
    public function updatePhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::PHONE, OTPType::UPDATE_PHONE, $request);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.phone_code_sent'), 'user' => $entity->refresh()->getResource()];
    }


    /**
     * Resend the verification code to the user's phone number
     *
     * @param $request
     * @return array
     */
    public function resendPhoneCode($request): array
    {
        return $this->updatePhone($request);
    }

    /**
     * Verify the user's phone number
     *
     * @param $request
     * @return array
     */
    public function verifyPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::UPDATE_PHONE, OTPThrough::PHONE, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {

            try {
                // Start a database transaction
                DB::beginTransaction();

                // delete the user's otp
                $entity->otps()->where(['type' => OTPType::UPDATE_PHONE, 'code' => $request->code])->delete();

                // UpdateProfile the user's phone number
                $entity->update(['phone' => $request->phone, 'country_code' => $request->country_code]);

                // Commit the transaction
                DB::commit();

                // Return success message
                return ['key' => 'success', 'msg' => __('auth.phone_updated'), 'user' => $entity->refresh()->getResource()];
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return ['key' => 'fail', 'msg' => $e->getMessage(), 'user' => []];
            }
        }
    }

    /**
     * Send a verification code to the user's email
     *
     * @param $request
     * @return array
     */
    public function updateEmail($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::EMAIL, OTPType::UPDATE_EMAIL, $request);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.email_code_sent'), 'user' => $entity->refresh()->getResource()];
    }

    /**
     * Resend the verification code to the user's email
     *
     * @param $request
     * @return array
     */
    public function resendEmailCode($request): array
    {
        return $this->updateEmail($request);
    }


    /**
     * Verify the user's email
     *
     * @param $request
     * @return array
     */
    public function verifyEmail($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::UPDATE_EMAIL, OTPThrough::EMAIL, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {

            try {
                // Start a database transaction
                DB::beginTransaction();

                // delete the user's otp
                $entity->otps()->where(['type' => OTPType::UPDATE_EMAIL, 'code' => $request->code])->delete();

                // UpdateProfile the user's email
                $entity->update(['email' => $request->email]);

                // Commit the transaction
                DB::commit();

                // Return success message
                return ['key' => 'success', 'msg' => __('auth.email_updated'), 'user' => $entity->refresh()->getResource()];
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return ['key' => 'fail', 'msg' => $e->getMessage(), 'user' => []];
            }
        }
    }

    /**
     * UpdateProfile the user's device language
     *
     * @param $request
     * @return array
     */
    public function changeLang($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // UpdateProfile the user's current device language
        $entity->currentDevice()->update(['preferred_locale' => $request->lang]);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.lang_updated'), 'user' => $entity->refresh()->getResource()];
    }


    /**
     * BEGAIN::Second Scenario for updating the phone number
     */

    /**
     * Send a verification code to the user's old phone number
     * @param $request
     * @return array
     */
    public function sendCodeToOldPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::PHONE, OTPType::PHONE_OWNERSHIP, $request);

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.phone_code_sent'), 'user' => $entity->refresh()->getResource()];
    }


    /**
     * Verify the user's old phone number
     * @param $request
     * @return array
     */
    public function verifyOldPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::PHONE_OWNERSHIP, OTPThrough::PHONE, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {
            // delete the user's otp
            $entity->otps()->where(['type' => OTPType::PHONE_OWNERSHIP, 'code' => $request->code])->delete();

            // Return success message
            return ['key' => 'success', 'msg' => __('auth.phone_ownership'), 'user' => $entity->refresh()->getResource()];
        }
    }


    /**
     * Send a verification code to the user's new phone number
     * @param $request
     * @return array
     */
    public function sendCodeToNewPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Send verification code to the target entity
        $entity->sendVerificationCode(OTPThrough::PHONE, OTPType::UPDATE_PHONE, $request);

        // Get the OTP code
        $code = $entity->otps()->where(['type' => OTPType::UPDATE_PHONE, 'identifier' => $request->phone, 'country_code' => $request->country_code])->first()->code;

        // Prepare the data to be returned
        $data = ['phone' => $request->phone, 'country_code' => $request->country_code, 'code' => $code];

        // Return success message
        return ['key' => 'success', 'msg' => __('auth.new_phone_code_sent'), 'data' => $data];
    }


    /**
     * Verify the user's new phone number
     * @param $request
     * @return array
     */
    public function verifyNewPhone($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // Check if the user has the otp
        $success = $entity->checkOtp($request->code, OTPType::UPDATE_PHONE, OTPThrough::PHONE, $request);

        if ($success == 'fail') {
            return ['key' => 'fail', 'msg' => __('auth.code_invalid'), 'user' => []];
        } elseif ($success == 'expired') {
            return ['key' => 'fail', 'msg' => __('auth.code_expired'), 'user' => []];
        } else {

            try {
                // Start a database transaction
                DB::beginTransaction();

                // delete the user's otp
                $entity->otps()->where(['type' => OTPType::UPDATE_PHONE, 'code' => $request->code])->delete();

                // UpdateProfile the user's phone number
                $entity->update(['phone' => $request->phone, 'country_code' => $request->country_code]);

                // Commit the transaction
                DB::commit();

                // Return success message
                return ['key' => 'success', 'msg' => __('auth.phone_updated'), 'user' => $entity->refresh()->getResource()];
            } catch (\Exception $e) {
                // Rollback the transaction in case of an error
                DB::rollback();

                return ['key' => 'fail', 'msg' => $e->getMessage(), 'user' => []];
            }
        }
    }


    /**
     * END::Second Scenario for updating the phone number
     */


    /**
     * UpdateProfile language
     * @param $request
     * @return array
     */
    public function updateLanguage($request): array
    {
        // Get the currently authenticated entity
        $entity = auth()->user();

        // UpdateProfile the user's language
        $entity->updateLang();

        // Set the application locale to the new language
        App::setLocale($request->lang);

        // Return success message
        return ['key' => 'success', 'msg' => __('apis.updated'), 'user' => $entity->refresh()->getResource()];
    }



    /**
     * Store the user's contacts
     * @param $request
     * @return array
     */
    // public function contact($request): array
    // {
    //     Contact::create($request);

    //     // Return success message
    //     return ['key' => 'success', 'msg' => __('apis.messageSended')];
    // }
}
