<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Order;
use App\Models\Farmer;
use App\Models\Product;
use Filament\Forms\Get;
use Illuminate\Http\Request;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class AdminForm extends Controller
{
    public static function farm(): array
    {
        return [
            Section::make('Farm Information')
                ->description('Please provide the essential details for creating a new farm record. This information is crucial for managing your farms effectively.')
                ->schema([

                    Wizard::make([
                        Wizard\Step::make('Farm Details')
                            ->schema([
                                Group::make()
                                ->columns([
                                    'sm' => 2,
                                    'md' => 4,
                                    'lg' => 6,
                                    'xl' => 8,
                                    '2xl' => 12,
                                ])
                                ->columnSpanFull()
        
        
                                ->schema([
                                    TextInput::make('farm_name')
        
                                        ->required()
                                        ->columnSpan([
                                            'sm' => 2,
                                            'md' => 4,
                                            'lg' => 4,
        
                                        ]),
                                    // text input location
                                    TextInput::make('location')
                                        ->required()
                                        ->columnSpan([
                                            'sm' => 2,
                                            'md' => 4,
                                            'lg' => 4,
        
                                        ]),
                                    // text input farm size
                                    TextInput::make('farm_size')
                                        ->required()
                                        ->columnSpan([
                                            'sm' => 2,
                                            'md' => 4,
                                            'lg' => 4,
        
                                        ]),
                                    // text input description
                                    RichEditor::make('description')
                                        ->columnSpanFull()
                                        ->required()
                                        ->toolbarButtons([
                                            'attachFiles',
                                            'blockquote',
                                            'bold',
                                            'bulletList',
                                            'codeBlock',
                                            'h2',
                                            'h3',
                                            'italic',
                                            'link',
                                            'orderedList',
                                            'redo',
                                            'strike',
                                            'underline',
                                            'undo',
                                        ]),
                                ])->columnSpanFull()
                            ]),
                            Wizard\Step::make('Farm Documents')
                            ->schema([
                                TableRepeater::make('farmer_documents')
                                ->relationship('documents')
                                ->columnSpanFull()
                                ->maxItems(10)
                                ->columnWidths([
        
                                    'name' => '200px',
                                ])
                                ->maxItems(6)
        
                                ->withoutheader()
                                ->schema([
        
                                    // text input name
                                    TextInput::make('name')
                                        ->required()
                                        ->columnSpan([
                                            'sm' => 2,
                                            'md' => 4,
                                            'lg' => 6,
                                            'xl' => 8,
                                            '2xl' => 12,
                                        ]),
                                    SpatieMediaLibraryFileUpload::make('file')->required()
                                   
        
                                ]),
                            ]),
                    ]),
                   
                        


                ])->columnSpanFull(),



        ];
    }

    //manage farmform
    public static function manageFarmForm():array{
        return [
            Select::make('status')

            ->live(debounce: 500)
            ->options(function (Model $record) {
                return $record->getAvailableStatusTransitions();
            })
            

        ->required(),


         // Status Reason Textarea
    Textarea::make('remarks')
    ->label('Why?')
    ->helperText('Kindly provide a reason for your decision')
    ->columnSpanFull()
    ->required()
    ->rows(5)
    ->hidden(function (Get $get) {
        
        return !in_array($get('status'), [Farmer::STATUS_BLOCKED, Farmer::STATUS_REJECTED]);
    }),
        ];
    }
}
