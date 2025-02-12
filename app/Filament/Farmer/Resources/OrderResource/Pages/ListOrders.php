<?php

namespace App\Filament\Farmer\Resources\OrderResource\Pages;

use App\Filament\Farmer\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array{
        return [
          'all' => Tab::make('All Orders'),
          'Pending'=> Tab::make('Pending')->modifyQueryUsing(fn (Builder $query) => $query->pending()),
          'Confirmed'=> Tab::make('Confirmed')->modifyQueryUsing(fn (Builder $query): mixed => $query->confirmed()),
          'Shipped '=> Tab::make('Shipped')->modifyQueryUsing(fn (Builder $query): mixed => $query->shipped()),
          'Out for Delivery'=> Tab::make('Out for Delivery')->modifyQueryUsing(fn (Builder $query) => $query->outForDelivery()),
          'Completed'=> Tab::make('Completed')->modifyQueryUsing(fn (Builder $query) => $query->completed()),
          'Cancelled'=> Tab::make('Cancelled')->modifyQueryUsing(fn (Builder $query) => $query->cancelled()),
          'Returned'=> Tab::make('Returned')->modifyQueryUsing(fn (Builder $query) => $query->returned()),
        ];
      }
}
