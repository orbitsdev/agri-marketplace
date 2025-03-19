<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Http\Controllers\FilamentForm;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'zondicon-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(FilamentForm::userForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                  TextColumn::make('fullName')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('last_name', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%");
                    }, isIndividual: true, isGlobal:false)->label('Full Name'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(isIndividual: true,),

                // Tables\Columns\TextColumn::make('role'),

                Tables\Columns\TextColumn::make('role')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    User::FARMER => 'success',
                    User::BUYER => 'info',
                    User::ADMIN=> 'warning',

                    default => 'gray'
                }),
                ToggleColumn::make('is_active')->label('Active/Disabled'),
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
                SelectFilter::make('role')
                ->options(User::ROLE_OPTIONS)->searchable()->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->isNotSuperAdmin()->latest())

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
