<?php

namespace App\Http\Controllers\StaticPage;

use App\Http\Controllers\Controller;
use App\Http\Resources\StaticPage\StaticPageResource;
use App\Models\StaticPage;
use Illuminate\Http\JsonResponse;

class StaticPageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(StaticPageResource::collection(StaticPage::all()));
    }

    public function show($slug): JsonResponse
    {
        return response()->json(StaticPageResource::make(StaticPage::where('slug', $slug)->first()));
    }
}
