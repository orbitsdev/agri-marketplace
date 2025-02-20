<?php

namespace App\Observers;

use App\Models\Comment;
use Filament\Notifications\Notification;
class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {


    if($comment->buyer)

    Notification::make()
    ->title('Saved successfully')
    ->sendToDatabase($recipient);
    }


}
