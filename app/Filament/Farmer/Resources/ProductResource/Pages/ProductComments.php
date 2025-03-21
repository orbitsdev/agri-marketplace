<?php

namespace App\Filament\Farmer\Resources\ProductResource\Pages;

use App\Models\Comment;
use Filament\Actions\Action;
use App\Filament\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MessageCreated;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Farmer\Resources\ProductResource;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ProductComments extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.farmer.resources.product-resource.pages.product-comments';

    use InteractsWithRecord;



    public function getHeading(): string
    {
        $item = $this->record->product_name;
        return __($item . ' Messages');
    }

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }


public function addReplyAction(): Action
{
    return Action::make('addReply')
        ->label('Reply')
        ->size('xs')
        ->extraAttributes([
            'style' => 'font-weight:normal; color:blue;'
        ])
        // ->icon('heroicon-o-reply')
        ->link()
        ->modalHeading('Add Reply')
        ->color('primary')
        ->form([
            Textarea::make('content')
                ->required()
                ->label('Reply Content')
                ->columnSpanFull()
                ->helperText('Write your reply to this comment.')
                ->rows(4),
        ])
        ->action(function (array $data, array $arguments) {
            DB::beginTransaction();

            try {
                $farmer = Auth::user()->farmer;

                if (!$farmer) {
                    throw new \Exception('Only farmers can reply to comments.');
                }

                $commentId = $arguments['record'];
                $parentComment = Comment::findOrFail($commentId);


                if ($parentComment->parent_id !== null) {
                    throw new \Exception('You can only reply to top-level comments.');
                }


                Comment::create([
                    'product_id' => $parentComment->product_id,
                    'buyer_id' => $parentComment->buyer_id,
                    'farmer_id' => $farmer->id,
                    'content' => $data['content'],
                    'creator' => 'Farmer',
                    'parent_id' => $commentId,
                ]);

                DB::commit();


            $receiver = $parentComment->buyer;
            $sender = Auth::user();
            $product = $parentComment->product;


            $receiver->notify(new MessageCreated(
                'message_reply',
                $sender->full_name . ' replied to your comment on "' . $product->product_name . '"',
                $data['content'],               // The reply content
                $sender->full_name,             // Sender name
                $receiver->full_name,           // Receiver name
                $sender->id,                    // Sender ID
                $receiver,                      // Receiver model
                route('product.details', ['code'=> $product->code,'slug' => $product->slug]) // Link to the product or comment
            ));


                Notification::make()
                ->title('Reply Sent')
                ->body('Your reply has been successfully added.')
                ->success()
                ->send();
            } catch (\Exception $e) {
                DB::rollBack();
                Notification::make()
                    ->title('Error')
                    ->body('Failed to send the reply. ' . $e->getMessage())
                    ->danger()
                    ->send();
            }
        });
}

public function deleteMessageAction(): Action
{
    return Action::make('deleteMessage')
        ->label('Delete')
        ->iconButton()
        ->color('danger')
        ->icon('heroicon-o-trash')
        ->requiresConfirmation()
        ->modalHeading('Confirm Delete')
        ->modalDescription('Are you sure you want to delete this message? This action cannot be undone.')
        ->action(function (array $arguments) {
            try {
                $commentId = $arguments['record'];

                DB::beginTransaction();

                // Ensure the comment exists and the user is authorized to delete it
                $comment = Comment::findOrFail($commentId);
                $comment->delete();

                DB::commit();

                Notification::make()
                ->title('Message Deleted')
                ->body('The message has been successfully deleted.')
                ->success()
                ->send();
            } catch (\Exception $e) {
                DB::rollBack();

                Notification::make()
                ->title('Error')
                ->body('Failed to delete the reply. ' . $e->getMessage())
                ->danger()
                ->send();
            }
        });
}

}
