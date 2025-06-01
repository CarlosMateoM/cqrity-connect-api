<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('rfid.register', function ($user) {
    return true;
});

Broadcast::channel('esp32', function ($user) {
    return true;
});
