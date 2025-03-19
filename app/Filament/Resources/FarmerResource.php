<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Farmer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Actions\StaticAction;
use App\Http\Controllers\AdminForm;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Filament\Support\Enums\MaxWidth;
use App\Http\Controllers\FilamentForm;
use Filament\Infolists\Components\View;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\FarmerResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FarmerResource\RelationManagers;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;

    protected static ?string $navigationIcon = 'phosphor-farm';
    protected static ?string $navigationLabel = 'Farms';
    protected static ?int $navigationSort = 2;

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // SpatieMediaLibraryImageEntry::make('user.image')->label('Profile'),
                TextEntry::make('user.fullName')
                    ->label('Farm Owner'),


                TextEntry::make('farm_name')
                    ->label('Farm Name'),

                TextEntry::make('location')
                    ->label('Location'),

                TextEntry::make('farm_size')
                    ->label('Farm Size'),


                TextEntry::make('description')
                    ->label('Description')->markdown()->columnSpanFull(),



                // TextEntry::make('created_at')
                //     ->label('Created At')
                //     ->dateTime()
                //     ->dateTimeTooltip(),

                // TextEntry::make('updated_at')
                //     ->label('Updated At')
                //     ->dateTime()
                //     ->dateTimeTooltip(),

                // RepeatableEntry::make('documents')
                // ->columnSpanFull()
                //     ->schema([
                //     // TextEntry::make('name'),
                //         View::make('infolists.components.file-link')


                //     ])
                //     ->columns(1)
                // RepeatableEntry::make('documents')
                // ->columnSpanFull()
                //     ->schema([
                //     // TextEntry::make('name'),
                //         View::make('infolists.components.file-link')


                //     ])
                //     ->contained(false)
                //     ->columns(1),

                    TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Farmer::STATUS_PENDING => 'info',
                        Farmer::STATUS_APPROVED => 'success',
                        Farmer::STATUS_REJECTED => 'danger',
                        Farmer::STATUS_BLOCKED => 'danger',
                        default => 'gray',
                    }),

                    TextEntry::make('remarks')
                    ->label('Remarks')->markdown()->columnSpanFull()
                    ->hidden(function (Model $record) {

                        return !in_array($record->status, [Farmer::STATUS_BLOCKED, Farmer::STATUS_REJECTED]);
                    }),

            ]);
    }

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
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()->modalWidth('7xl'),
                    // Action::make('View')
                    // ->icon('heroicon-s-eye')
                    // ->modalSubmitAction(false)
                    // ->modalContent(function (Model $record) {
                    //     return view('livewire.farm-details', ['record' => $record]);
                    // })
                    // ->modalCancelAction(fn(StaticAction $action) => $action->label('Close'))
                    // ->closeModalByClickingAway(false)->modalWidth('7xl'),
                    Action::make('manage')
                        ->label('Manage')
                        ->icon('heroicon-s-pencil-square')
                        ->modalWidth('6xl')
                        ->fillForm(function (Model $record) {

                            $formData = ['status' => $record->status];

                            if (in_array($record->status, [Farmer::STATUS_REJECTED, Farmer::STATUS_BLOCKED])) {
                                $formData['remarks'] = $record->remarks;
                            }

                            return $formData;
                        })
                        ->form(AdminForm::manageFarmForm())
                        ->action(function (Model $record, array $data) {
                            if ($record->status === $data['status']) {
                                Notification::make()
                                    ->title('No Changes Made')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            // Validate remarks only if the new status requires it
                            if (in_array($data['status'], [Farmer::STATUS_REJECTED, Farmer::STATUS_BLOCKED]) && empty($data['remarks'])) {
                                Notification::make()
                                    ->title('Remarks Required')
                                    ->body('Please provide a reason for rejecting or blocking the farm.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Update the status and remarks
                            $record->update([
                                'status' => $data['status'],
                                'remarks' => $data['remarks'] ?? null,
                            ]);

                            Notification::make()
                                ->title('Farm Status Updated')
                                ->success()
                                ->send();
                        })
                        ->color('gray'),

                    Tables\Actions\EditAction::make('Update')->form(AdminForm::farm()),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
