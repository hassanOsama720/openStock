<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Complete;
use App\Http\Requests\Api\Auth\ForgetPassword;
use App\Http\Requests\Api\Auth\Login;
use App\Http\Requests\Api\Auth\Register;
use App\Http\Requests\Api\Auth\ResetPassword;
use App\Http\Requests\Api\Auth\VerifyForgetPassword;
use App\Http\Requests\Api\Profile\UpdatePassword;
use App\Http\Requests\Api\Profile\UpdateProfile;
use App\Http\Requests\Api\Profile\UpdateStore;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\ProfileBaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileBaseService $profileBaseService;
    public function __construct(ProfileBaseService $profileBaseService)
    {
        $this->profileBaseService = $profileBaseService;
    }

    public function getProfile(): JsonResponse
    {
        $res = $this->profileBaseService->getProfile();
        return response()->json($res);
    }

    public function updateProfile(UpdateProfile $request): JsonResponse
    {
        $res = $this->profileBaseService->updateProfile($request);
        return response()->json($res);
    }

    public function updateStore(UpdateStore $request): JsonResponse
    {
        $res = $this->profileBaseService->updateStore($request);
        return response()->json($res);
    }

    public function updatePassword(UpdatePassword $request): JsonResponse
    {
        $res = $this->profileBaseService->updatePassword($request);
        return response()->json($res);
    }
}
