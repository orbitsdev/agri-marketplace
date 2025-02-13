<?php

namespace App\Filament\Farmer\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminForm;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Farmer\Resources\OrderResource\Pages;
use App\Filament\Farmer\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'polaris-order-first-icon';
    protected static ?int $navigationSort = 3;


    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->myBuyersOrder() // Ensure this scope exists and filters appropriately
            ->pending() // Ensure this scope filters only pending orders
            ->count();
    
        return $count > 0 ? (string) $count : null;
    }
    

    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            // Order Details Section
            Infolists\Components\Section::make('Order Details')
                ->schema([
                    Infolists\Components\TextEntry::make('order_number'),
                    Infolists\Components\TextEntry::make('shipped_date'),
                    Infolists\Components\TextEntry::make('delivery_date'),
                    // Infolists\Components\TextEntry::make('total.'),
                    ViewEntry::make('total')
    ->view('infolists.components.order-total')
                       

                    // Infolists\Components\SelectEntry::make('farmer_id')
                    //     ->label('Farmer')
                    //     ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->farm_name}"),
                ])
                ->columns(2),

               

                Infolists\Components\Section::make('Buyer ')
                ->schema([
                   
                        Infolists\Components\TextEntry::make('buyer.full_name')->label('Name'),
                //     Infolists\Components\RepeatableEntry::make('items')
                //         ->schema([
                //             Infolists\Components\TextEntry::make('product.product_name')
                //                 ->label('Product'),
                //             Infolists\Components\TextEntry::make('quantity')
                //                 ->label('Quantity'),
                //         ])
                //         ->contained(false)
                //         ->label('Items')
                        
                //         ->columnSpan('full'),
                    

                
                ]),
                Infolists\Components\Section::make('Items')
                ->schema([
                //     Infolists\Components\RepeatableEntry::make('items')
                //         ->schema([
                //             Infolists\Components\TextEntry::make('product.product_name')
                //                 ->label('Product'),
                //             Infolists\Components\TextEntry::make('quantity')
                //                 ->label('Quantity'),
                //         ])
                //         ->contained(false)
                //         ->label('Items')
                        
                //         ->columnSpan('full'),

                ViewEntry::make('Order Items')->label('')
                ->view('infolists.components.order-items')->columnSpanFull(),
                ]),

            // Shipping Address Section
            Infolists\Components\Section::make('Shipping Address')
                ->schema([
                    Infolists\Components\TextEntry::make('phone')
                        ->label('Phone'),
                    Infolists\Components\TextEntry::make('region')
                        ->label('Region'),
                    Infolists\Components\TextEntry::make('province')
                        ->label('Province'),
                    Infolists\Components\TextEntry::make('city_municipality')
                        ->label('City/Municipality'),
                    Infolists\Components\TextEntry::make('barangay')
                        ->label('Barangay'),
                    Infolists\Components\TextEntry::make('street')
                        ->label('Street'),
                    Infolists\Components\TextEntry::make('zip_code')
                        ->label('Zip Code'),
                ])
                ->columns(2),

            // Payment Details Section
            Infolists\Components\Section::make('Payment Details')
                ->schema([
                    Infolists\Components\TextEntry::make('payment_method')
                        ->label('Payment Method'),
                    Infolists\Components\TextEntry::make('payment_reference')
                        ->label('Payment Reference'),
                ])
                ->columns(2),

            // Order Status Section
            Infolists\Components\Section::make('Order Status')
                ->schema([
                    Infolists\Components\TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Order::PENDING => 'gray', // Pending orders are neutral (gray).
                        Order::PROCESSING => 'info', // Processing orders use informational color (blue).
                        Order::CONFIRMED => 'success', // Confirmed orders are marked as successful (green).
                        Order::SHIPPED => 'primary', // Shipped orders are highlighted as primary (blue).
                        Order::OUT_FOR_DELIVERY => 'warning', // Out for delivery orders use warning color (yellow).
                        Order::COMPLETED => 'success', // Completed orders use success color (green).
                        Order::CANCELLED => 'danger', // Cancelled orders use danger color (red).
                        Order::RETURNED => 'danger', // Returned orders also use danger color (red).
                        default => 'gray', // Any unknown state defaults to neutral (gray).
                    }),
                
                    Infolists\Components\TextEntry::make('order_date')
                        ->label('Order Date'),
                    Infolists\Components\TextEntry::make('shipped_date')
                        ->label('Shipped Date'),
                    Infolists\Components\TextEntry::make('delivery_date')
                        ->label('Delivery Date'),

                        TextEntry::make('remarks')
                        ->label('Remarks')->markdown()->columnSpanFull()
                        ->hidden(function (Model $record) {
            
                            return !in_array($record->status, [Order::CANCELLED, Order::RETURNED]);
                        }),
                ])
                ->columns(2),

            // Order Items Section
            
        ]);
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema(AdminForm::orderForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('buyer.fullName')
                //     ->searchable(query: function (Builder $query, string $search): Builder {
                //         return $query->where('last_name', 'like', "%{$search}%")
                //             ->orWhere('first_name', 'like', "%{$search}%")
                //             ->orWhere('middle_name', 'like', "%{$search}%");
                //     })->label('Farm Owner'),
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('buyer.fullName'),
                Tables\Columns\TextColumn::make('phone')->prefix('+63'),

                // Tables\Columns\TextColumn::make('region')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('province')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('city_municipality')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('barangay')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('street')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('zip_code')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_reference')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('status'),
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
                Tables\Columns\TextColumn::make('order_date')
                    ->dateTime()
                    ->sortable() ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shipped_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('delivery_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Tables\Columns\TextColumn::make('total')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\IconColumn::make('is_received')
                //     ->boolean(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options(Order::ADMIN_ORDER_MANAGE_OPTIONS)->searchable()->multiple()
            ])
            ->actions([

                ActionGroup::make([




                    Tables\Actions\Action::make('Manage')
                    ->icon('heroicon-s-pencil-square')
                        ->url(fn(Model $record): string => route('filament.farmer.resources.orders.manager-order', ['record' => $record])),
                  

                        Tables\Actions\ViewAction::make()->modalWidth(MaxWidth::SevenExtraLarge),
                    Tables\Actions\EditAction::make('update'),

                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->notProcessing()->myBuyersOrder()->latest())
        ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'manager-order' => Pages\ManageOrderRequest::route('/{record}/manage-order'),

        ];
    }
}
