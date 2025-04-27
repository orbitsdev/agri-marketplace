<?php

namespace App\Filament\Farmer\Resources\FarmerResource\Widgets;

use App\Models\Product;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class ListOfAlertLevelProduct extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->heading('Products Low on Stock')
            ->columns([
            TextColumn::make('product_name')
                    ->label('Product Name')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Current Quantity')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('alert_level')
                    ->label('Alert Level')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Product::AVAILABLE => 'success',
                        Product::SOLD => 'gray',
                        Product::PENDING => 'warning',
                        default => 'secondary',
                    }),
            ]);
    }

    protected function getQuery(): Builder
    {
        return Product::query()
            ->withRelations()
            ->approveFarmer()
            ->published()
            ->whereColumn('quantity', '<=', 'alert_level')
            ->where('farmer_id', auth()->user()->farmer?->id);
    }
}
