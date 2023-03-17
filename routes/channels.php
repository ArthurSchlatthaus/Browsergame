<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('notifyChannel.{api_token}', function ($user,$api_token) {
    return $user->api_token === $api_token;
});

Broadcast::channel('successChannel.{api_token}', function ($user,$api_token) {
    return $user->api_token === $api_token;
});

Broadcast::channel('infoChannel.{api_token}', function ($user,$api_token) {
    return $user->api_token === $api_token;
});
Broadcast::channel('errorChannel.{api_token}', function ($user,$api_token) {
    return $user->api_token === $api_token;
});