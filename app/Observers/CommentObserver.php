<?php

namespace App\Observers;

use App\Models\Comment;

use App\Filament\Notification;
use Filament\Notifications\Actions\Action;

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


            $recipient = $farmer->user;
            $notificationTitle = "{$buyer->full_name} commented on Product '{$product->product_name}' ({$product->code})";

            if ($comment->parent_id) {
                $parentComment = Comment::find($comment->parent_id);

                if ($parentComment) {
                    $notificationTitle = "{$buyer->full_name} replied to a comment on '{$product->product_name}' ({$product->code})";
                }
            }

            Notification::make()
                ->title($notificationTitle)
                ->body("\"{$comment->content}\"")
                ->sendToDatabase($recipient)
                ->size('YOOW')
                // ->mydata([
                //     'comment_id' => $comment->id,
                //     'product_id' => $product->id,
                //     'farmer_id' => $farmer->id,
                //     'buyer_id' => $buyer->id,
                //     'comment_content' => $comment->content,
                // ])


                ;
        }




    }





}
