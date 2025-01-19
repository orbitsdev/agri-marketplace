<?php

namespace App\Filament\Farmer\Resources\FarmerResource\Widgets;

use Filament\Tables;
use App\Models\Order;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {   

        return $table
            ->query(Order::query()->myBuyersOrder()->pending())
            ->heading('Latest Pending Orders')

            ->columns([
                TextColumn::make('buyer.fullName')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('last_name', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%");
                    })->label('Farm Owner'),
                Tables\Columns\TextColumn::make('order_number')
                ->searchable(),


            Tables\Columns\TextColumn::make('payment_method')
                ->searchable(),
            Tables\Columns\TextColumn::make('payment_reference')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    Order::PENDING => 'gray',
                    Order::PROCESSING => 'info',
                    Order::CONFIRMED => 'success',
                    Order::SHIPPED => 'primary',
                    Order::OUT_FOR_DELIVERY => 'warning',
                    Order::COMPLETED => 'success',
                    Order::CANCELLED => 'danger',
                    Order::RETURNED => 'danger',
                    default => 'grey',
                }),
            ViewColumn::make('Total')->view('tables.columns.table-total-order'),
           
            ]);
    }
}
