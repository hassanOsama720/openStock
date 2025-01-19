<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticPage\StaticPageController;

Route::get('/', function () {
    return "Hello World";
});
Route::get('/static-pages', [StaticPageController::class, 'index']);
