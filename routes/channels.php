<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
 return (int) $user->id === (int) $id;
});
Broadcast::channel('online', function ($user) {
    // You can add any authorization logic here.
    // For this example, any authenticated user can join.
    // If the user is not authenticated, this callback will not even be triggered.

    // The array you return will be available to other users on the channel.
    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => 'https://i.pravatar.cc/150?u=' . $user->id // Example avatar
    ];
});
