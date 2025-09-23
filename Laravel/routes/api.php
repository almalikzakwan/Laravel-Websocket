<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/send-message', function (Request $request) {
    event(new \App\Events\MessageSent($request->message ?? 'Hello Reverb!'));
    return ['status' => 'Message broadcasted'];
});
