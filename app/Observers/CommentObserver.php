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

        if ($comment->creator === 'Buyer') {
            $product = $comment->product;
            $buyer = $comment->buyer;
            $farmer = $comment->farmer;

            Notification::make()
                ->title("{$buyer->full_name} commented on Product \n'{$product->product_name}' ( {$product->code})\n")
                ->body("{$comment->content}")
                ->sendToDatabase($farmer->user);
        }



    }





}
