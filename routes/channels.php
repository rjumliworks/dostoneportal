<?php

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('session', function () {
    return true;
});

Broadcast::channel('capacity', function () {
    return true;
});

Broadcast::channel('vip', function () {
    return true;
});

// Signaling only (SDP/ICE for the Scanner.vue -> FaceRecognitionPage.jsx
// WebRTC video handoff) - no video ever touches this channel. Public like
// 'vip' above since the display side is a guest app with no session here.
Broadcast::channel('vip-signal', function () {
    return true;
});
