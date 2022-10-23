<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// I would assume in the real world this would be in the above route group so it is behind auth.  But Readme.md says to assume that that is out of scope
Route::apiResource('applications', ApplicationController::class)->only(['index', 'show']);
