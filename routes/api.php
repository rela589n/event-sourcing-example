<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UniversalController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get(
    '/user',
    function (Request $request) {
        return $request->user();
    }
);

Route::post('user/register', [UserController::class, 'register']);
Route::post('user/change-login', [UserController::class, 'changeLogin']);
Route::post('user/change-password', [UserController::class, 'changePassword']);

Route::post('chats/create', [ChatController::class, 'create']);
Route::post('chats/join', [ChatController::class, 'joinChat']);
Route::post('chats/leave', [ChatController::class, 'leaveChat']);
Route::post('chats/kick', [ChatController::class, 'kickOut']);

Route::get('chats/{chat}', [ChatController::class, 'show']);

Route::post('messages/write', [MessageController::class, 'write']);
Route::post('messages/edit', [MessageController::class, 'edit']);
Route::post('messages/delete', [MessageController::class, 'delete']);

Route::get('messages', [MessageController::class, 'index']);

Route::post('universal/command', [UniversalController::class, 'runCommand']);
