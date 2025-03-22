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

// Broadcast::channel('App.Models.User.*', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('test-channel', function () {
    return true;
});



// Broadcast::channel('notifications.{userId}', function ($user, $userId) {
//     return (int) $user->id === (int) $userId; // ✅ Allow only the owner to listen
// });

Broadcast::channel('test.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});



Broadcast::channel('private-participant.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('private-conversation.{conversationId}', function ($user, $conversationId) {
    return $user->canAccessConversation($conversationId);
});




Broadcast::channel('wirechat.user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('wirechat.group.{groupId}', function ($user, $groupId) {
    return true; // You could add group access logic here
});
