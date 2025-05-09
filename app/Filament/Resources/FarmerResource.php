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
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\FarmerResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FarmerResource\RelationManagers;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry\TextEntrySize;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;

    protected static ?string $navigationIcon = 'phosphor-farm';
    protected static ?string $navigationLabel = 'Farmers';
    protected static ?int $navigationSort = 2;

   public static function getNavigationBadge(): ?string
{
    return static::getModel()::where('status', 'Pending')->count();
}

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Farm Owner Information Section
                Section::make('Farm Owner Information')
                    ->schema([
                        TextEntry::make('user.fullName')
                            ->label('Owner Name')
                            ->size(TextEntry\TextEntrySize::Large),

                        TextEntry::make('user.email')
                            ->label('Email')
                            ->icon('heroicon-m-envelope'),

                        TextEntry::make('user.phone')
                            ->label('Phone')
                            ->icon('heroicon-m-phone'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                // Farm Details Section
                Section::make('Farm Details')
                    ->schema([
                        TextEntry::make('farm_name')
                            ->label('Farm Name')
                            ->size(TextEntry\TextEntrySize::Large),

                        TextEntry::make('location')
                            ->label('Location')
                            ->icon('heroicon-m-map-pin'),

                        TextEntry::make('farm_size')
                            ->label('Farm Size')
                            ->icon('heroicon-m-square-2-stack'),

                        TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->collapsible(),

                // Application Status Section
                Section::make('Application Status')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Current Status')
                            ->badge()
                            ->size(TextEntry\TextEntrySize::Large)
                            ->color(fn(string $state): string => match ($state) {
                                Farmer::STATUS_PENDING => 'info',
                                Farmer::STATUS_APPROVED => 'success',
                                Farmer::STATUS_REJECTED => 'danger',
                                Farmer::STATUS_BLOCKED => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('remarks')
                            ->label('Remarks')
                            ->markdown()
                            ->columnSpanFull()
                            ->hidden(function (Model $record) {
                                return !in_array($record->status, [Farmer::STATUS_BLOCKED, Farmer::STATUS_REJECTED]);
                            }),

                        TextEntry::make('created_at')
                            ->label('Registered On')
                            ->dateTime()
                            ->icon('heroicon-m-calendar'),

                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime()
                            ->icon('heroicon-m-clock'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Required Documents Section
                Section::make('Required Documents')
                    ->schema([
                        RepeatableEntry::make('farmer_requirements')
                            ->schema([
                                View::make('infolists.components.file-link')
                            ])
                            ->contained(false)
                            ->columns(1),
                    ])
                    ->collapsible(),
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
                TextColumn::make('user.is_active')
                ->label('Account ')
                ->badge()
                ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                ->sortable(),
                TextColumn::make('status')
                ->label('Application ')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    Farmer::STATUS_PENDING => 'info',
                    Farmer::STATUS_APPROVED => 'success',
                    Farmer::STATUS_REJECTED => 'danger',
                    Farmer::STATUS_BLOCKED => 'danger',
                    default => 'gray'
                })
                ->sortable(),
                // Farm Owner Information
                TextColumn::make('user.fullName')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user',function($q) use($search){
                            $q->where('last_name', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%");
                        });
                    })
                    ->label('Farm Owner')
                    ->sortable(),

                    ViewColumn::make('files')
                    ->view('tables.columns.farm-documents-column')
                    ->label('Documents'),

                TextColumn::make('user.email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->label('Email')
                    ->copyable()
                    ->sortable(),

                // Account Status


                // Farm Information
                TextColumn::make('farm_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->sortable(),

                TextColumn::make('farm_size')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault:true)
                    ->sortable(),

                // Application Status


                // Documents


                // Timestamps
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Action::make('export_farmers_excel')
                    ->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn() => route('export.farmers.excel'), shouldOpenInNewTab: true),

                Action::make('print_farmers_report')
                    ->label('Print Report')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn() => route('reports.printable.farmers'), shouldOpenInNewTab: true)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('active_status')
                    ->label('Account Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'] === 'active', function (Builder $query): Builder {
                            return $query->whereHas('user', function (Builder $query): Builder {
                                return $query->where('is_active', true);
                            });
                        })->when($data['value'] === 'inactive', function (Builder $query): Builder {
                            return $query->whereHas('user', function (Builder $query): Builder {
                                return $query->where('is_active', false);
                            });
                        });
                    })
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('Export Documents')
                    ->label('Farmer Documents Lists')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Model $record) => route('export.farmer.documents', ['farmer' => $record->id]), shouldOpenInNewTab: true)
                    ->hidden(fn (Model $record) => !$record->documents()->exists()),
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
                            // Load the farmer with requirements for the form
                            $record->load(['farmerRequirements.requirement', 'farmerRequirements.media']);

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

                            // Get the old status for comparison
                            $oldStatus = $record->status;
                            $newStatus = $data['status'];

                            // Update the status and remarks
                            $record->update([
                                'status' => $newStatus,
                                'remarks' => $data['remarks'] ?? null,
                            ]);

                            // Send SMS notification to farmer based on status
                            if ($record->user && $record->user->phone) {
                                // Original TeamSSProgramSmsService code (commented out as it's expired)
                                // $smsService = app(\App\Services\TeamSSProgramSmsService::class);
                                
                                // Using SmsService instead
                                $smsService = app(\App\Services\SmsService::class);
                                
                                $farmerName = $record->user->fullName;
                                $message = '';

                                switch ($newStatus) {
                                    case Farmer::STATUS_APPROVED:
                                        $message = "Hello {$farmerName}, your farm registration has been APPROVED! You can now start using the Agri-Marketplace platform to sell your products.";
                                        break;
                                    case Farmer::STATUS_REJECTED:
                                        $message = "Hello {$farmerName}, your farm registration has been REJECTED. Reason: {$data['remarks']}. Please update your information and resubmit.";
                                        break;
                                    case Farmer::STATUS_BLOCKED:
                                        $message = "Hello {$farmerName}, your farm account has been BLOCKED. Reason: {$data['remarks']}. Please contact support for assistance.";
                                        break;
                                    case Farmer::STATUS_PENDING:
                                        $message = "Hello {$farmerName}, your farm registration is now PENDING review. We will notify you once it has been processed.";
                                        break;
                                }

                                if (!empty($message)) {
                                    $smsService->sendSms($record->user->phone, $message);
                                }
                            }

                            Notification::make()
                                ->title('Farm Status Updated')
                                ->success()
                                ->send();
                        })
                        ->color('gray'),

                    Tables\Actions\EditAction::make('Update')->form(AdminForm::farm()),
                    Tables\Actions\DeleteAction::make()->color('gray'),
                ], ),
            ],position: ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])->groups([
                Group::make('status')
                    ->label('Application Status')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function (Model $record): string {
                        return match ($record->status) {
                            Farmer::STATUS_PENDING => 'Pending',
                            Farmer::STATUS_APPROVED => 'Approved',
                            Farmer::STATUS_REJECTED => 'Rejected',
                            Farmer::STATUS_BLOCKED => 'Blocked',
                            default => ucfirst($record->status),
                        };
                    }),
                Group::make('user.is_active')
                    ->label('Account Status')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function (Model $record): string {
                        return $record->user->is_active ? 'Active' : 'Inactive';
                    })
            ])
            ->defaultGroup('status')
            ->modifyQueryUsing(fn (Builder $query) => $query->latest()->whereHas('user')->with(['user','documents.media']))

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
            'index' => Pages\ListFarmers::route('/'),
            'create' => Pages\CreateFarmer::route('/create'),
            'edit' => Pages\EditFarmer::route('/{record}/edit'),
        ];
    }
}
