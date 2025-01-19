<?php

namespace App\Http\Controllers\ContactUsMessage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactUsMessage\StoreContactUsMessage;
use App\Services\ContactUsMessageService;
use Illuminate\Http\JsonResponse;

class ContactUsMessageController extends Controller
{
    private ContactUsMessageService $contactUsMessageService;
    public function __construct(ContactUsMessageService $contactUsMessageService)
    {
        $this->contactUsMessageService = $contactUsMessageService;
    }
    public function store(StoreContactUsMessage $request): JsonResponse
    {
        $res = $this->contactUsMessageService->store($request->validated());
        if ($res['status'] !== 'success') {
            return response()->json($res, 401);
        }
        return response()->json($res);
    }
}
