<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\Activate;
use App\Http\Requests\Api\Auth\Complete;
use App\Http\Requests\Api\Auth\ForgetPassword;
use App\Http\Requests\Api\Auth\Login;
use App\Http\Requests\Api\Auth\Register;
use App\Http\Requests\Api\Auth\Resend;
use App\Http\Requests\Api\Auth\ResetPassword;
use App\Http\Requests\Api\Auth\VerifyForgetPassword;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthenticationService $authenticationService;
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function login(Login $request): JsonResponse
    {
        $res = $this->authenticationService->login($request);
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        $res['user'] = UserResource::make($res['user']);
        return response()->json($res);
    }

    public function logout(Request $request): JsonResponse
    {
        $res = $this->authenticationService->logout($request->bearerToken());
        return response()->json($res);
    }

    public function register(Register $request): JsonResponse
    {
        $res = $this->authenticationService->register($request->validated());
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }

    public function activate(Activate $request): JsonResponse
    {
        $res = $this->authenticationService->activate($request);
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }

    public function resend(Resend $request): JsonResponse
    {
        $res = $this->authenticationService->resendCode($request);
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }

    public function complete(Complete $request): JsonResponse
    {
        $res = $this->authenticationService->completeProfile($request);
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }

    public function forgotPassword(ForgetPassword $request): JsonResponse
    {
        $res = $this->authenticationService->forgetPasswordSendCode($request);
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }

    public function verifyForgotPassword(VerifyForgetPassword $request): JsonResponse
    {
        $res = $this->authenticationService->forgetPasswordCheckCode($request);
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }

    public function resetPassword(ResetPassword $request): JsonResponse
    {
        $res = $this->authenticationService->resetPassword($request);
        if ($res['key'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }
}
