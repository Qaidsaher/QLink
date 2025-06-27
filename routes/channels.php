<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
 return (int) $user->id === (int) $id;
});
Broadcast::channel('online', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => 'https://i.pravatar.cc/150?u=' . $user->id // Example avatar
    ];
});
