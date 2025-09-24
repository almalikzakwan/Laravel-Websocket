<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

//Server health
Route::get('/health', function () {
    return ['status' => 'OK'];
});

Route::post('/send-message', function (Request $request) {
    event(new \App\Events\MessageSent($request->message ?? 'Hello Reverb!'));
    return ['status' => 'Message broadcasted'];
});
