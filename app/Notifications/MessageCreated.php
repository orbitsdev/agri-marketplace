<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageCreated extends Notification implements ShouldBroadcastNow
 ShouldQueue
{
    use Queueable;


    public $type;
    public $title;
    public $message;
    public $senderName;
    public $receiverName;
    public $senderId;
    public $receiver;
    public $route;

    public function __construct(
        $type,
        $title,
        $message,
        $senderName,
        $receiverName,
        $senderId,
        $receiver,
        $route
    ) {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->senderName = $senderName;
        $this->receiverName = $receiverName;
        $this->senderId = $senderId;
        $this->receiver = $receiver;
        $this->route = $route;
    }

    /**
     * Determine the notification delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Store notification in the database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'senderName' => $this->senderName,
            'receiverName' => $this->receiverName,
            'senderId' => $this->senderId,
            'receiverId' => $this->receiver->id,
            'profile_image' => $this->receiver->getImage(), // You can store the sender's profile image if needed
            'url' => $this->route,
            'time' => now(),
        ];
    }

    /**
     * Broadcast event name.
     */
    public function broadcastAs(): string
{
    return 'message.created';
}

    /**
     * Broadcast on a private channel specific to the receiver.
     */

    /**
     * Define the broadcasted event payload.
     */
    public function toBroadcast($notifiable)
    {
        return [
            'data' => $this->toDatabase($notifiable),
        ];
    }
    public function broadcastOn(): array
    {
        return [
            new Channel('notifications.'.$this->receiver->id),
        ];
    }
}
