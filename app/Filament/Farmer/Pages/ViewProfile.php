<?php

namespace App\Filament\Farmer\Pages;

use App\Models\Farmer;
use Filament\Pages\Page;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\View;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class ViewProfile extends Page
{

    use InteractsWithInfolists;
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.farmer.pages.view-profile';
    protected static bool $shouldRegisterNavigation  = false;

    public function productInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record(Auth::user())
            ->schema([
                // User Profile Section
                Section::make('User Profile')
                    ->columns(2)
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('image')
                            ->label('Profile')
                            ->columnSpanFull(),


                        TextEntry::make('fullName')
                            ->label('Farm Owner')
,
                        TextEntry::make('phone')
                            ->label('Phone Number'),

                        TextEntry::make('email')
                            ->label('Email Address'),
                    ]),

                // Farm Details Section
                Section::make('Farm Details')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('farmer.farm_name')
                            ->label('Farm Name')
,
                        TextEntry::make('farmer.location')
                            ->label('Location'),

                        TextEntry::make('farmer.farm_size')
                            ->label('Farm Size'),

                        TextEntry::make('farmer.description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                // Documents Section
                Section::make('Farm Documents')
                    ->collapsible()
                    ->schema([
                        RepeatableEntry::make('farmer.farmer_requirements')
                            ->label('Required Documents')
                            ->columnSpanFull()
                            ->schema([
                                View::make('infolists.components.file-link'),
                            ])
                            ->contained(false)
                            ->columns(1),
                    ]),

                // Status & Remarks Section
                Section::make('Status & Remarks')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('farmer.status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                Farmer::STATUS_PENDING => 'info',
                                Farmer::STATUS_APPROVED => 'success',
                                Farmer::STATUS_REJECTED => 'danger',
                                Farmer::STATUS_BLOCKED => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('farmer.remarks')
                            ->label('Remarks')
                            ->markdown()
                            ->columnSpanFull()
                            ->hidden(fn(Model $record) => !in_array($record->status, [Farmer::STATUS_BLOCKED, Farmer::STATUS_REJECTED])),
                    ]),
            ]);
    }

}
