<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Farmer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Http\Controllers\AdminForm;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FarmerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FarmerResource\RelationManagers;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;

    protected static ?string $navigationIcon = 'phosphor-farm';
    protected static ?string $navigationLabel = 'Farms';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(AdminForm::farm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              TextColumn::make('user.fullName')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('last_name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%");
                    })->label('Farm Owner'),
              TextColumn::make('farm_name')
                    ->searchable(),
              TextColumn::make('location')
                    ->searchable(),
              TextColumn::make('farm_size')
                    ->searchable(),
                    TextColumn::make('status')
                       ->badge()
                       ->color(fn(string $state): string => match ($state) {
                           Farmer::STATUS_PENDING => 'info',
                           Farmer::STATUS_APPROVED => 'success',
                           Farmer::STATUS_REJECTED => 'danger',
                           Farmer::STATUS_BLOCKED => 'danger',
   
                           default => 'gray'
                       }),
              TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
              TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->groups([
                Group::make('status')
                    ->titlePrefixedWithLabel(false),

            ])->defaultGroup('status');;
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
            'index' => Pages\ListFarmers::route('/'),
            'create' => Pages\CreateFarmer::route('/create'),
            'edit' => Pages\EditFarmer::route('/{record}/edit'),
        ];
    }
}
