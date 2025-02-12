<?php

namespace App\Filament\Farmer\Resources\ProductResource\Pages;

use App\Filament\Farmer\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
}

    public function getTabs(): array{
        return [
          'all' => Tab::make('All Products'),
          'Displayed'=> Tab::make('Displayed')->modifyQueryUsing(fn (Builder $query) => $query->published()),
          'Not Displayed'=> Tab::make('Not Displayed')->modifyQueryUsing(fn (Builder $query): mixed =>$query->unpublished()),
        ];
      }
}
