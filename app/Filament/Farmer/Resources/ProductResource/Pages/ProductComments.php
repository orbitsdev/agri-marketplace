<?php

namespace App\Filament\Farmer\Resources\ProductResource\Pages;

use App\Models\Comment;
use Filament\Actions\Action;
use App\Filament\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
}
