<?php

namespace App\Filament\Farmer\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FilamentForm;
use Filament\Support\Enums\ActionSize;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Farmer\Resources\ProductResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Filament\Farmer\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'healthicons-f-agriculture';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(FilamentForm::productForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


                // Tables\Columns\TextColumn::make('farmer_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(isIndividual:true),
                    SpatieMediaLibraryImageColumn::make('image') ->defaultImageUrl(url('/images/image-placeholder2.jpg')),
                Tables\Columns\TextColumn::make('product_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_description')->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),

                    Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Product::AVAILABLE => 'success',
                        Product::SOLD => 'green',
                        Product::PENDING => 'info',
                        default => 'gray',
                    }),

                    ToggleColumn::make('is_published')->label('Publish'),

                    Tables\Columns\TextColumn::make('comments_count')->counts('comments')->label('Messages'),

                // Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                SelectFilter::make('category')->label('Category')
    ->relationship('category', 'name')
    ->searchable()
    ->preload(),
                SelectFilter::make('status')
                ->options(Product::STATUS_OPTIONS)->multiple()
                ->searchable()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('View Messages') // Disable closing the modal by clicking outside
                    ->modalWidth('7xl')

                    ->label('Messages') // Add label for better UX
                    ->icon('heroicon-s-chat-bubble-left-right') // Optional: Add an icon for better UI
                    ->url(function (Model $record) {

                      return self::getUrl('messages',['record'=>$record->id]);

                    }, shouldOpenInNewTab: true)->visible(function(Model $record){
                        return $record->has_comments;
                    }),


                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()->color('gray'),

                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('status')
                    ->label('By Status')->titlePrefixedWithLabel(false),
                Group::make('category.name')
                    ->label('By Category')
                    ->titlePrefixedWithLabel(false),

            ])
            ->defaultGroup('category.name')
            ->modifyQueryUsing(fn (Builder $query) => $query->myProduct(Auth::user()->farmer->id))
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'messages' => Pages\ProductComments::route('/{record}/messages'),
        ];
    }
}
