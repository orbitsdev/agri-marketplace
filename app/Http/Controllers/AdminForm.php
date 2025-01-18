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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
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

    public static function orderForm() :array{
        return [
            // Order Details Section
            Section::make('Order Details')
                ->schema([
                  
    
                    Select::make('buyer_id')
                        ->label('buyer')
                        ->relationship(
                            'buyer', 
                            'last_name',
                            modifyQueryUsing: fn (Builder $query) => $query->where('role', '==', User::BUYER),
                            ) 
                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->fullName}")
                            ->searchable(['first_name', 'last_name'])
                        ->searchable()
                        ->preload()
                        ->required(),
    
                    // Select::make('farmer_id')
                    //     ->label('Farmer')
                    //     ->relationship('farmer', 'farm_name') // Assuming 'farm_name' is the display column
                    //     ->searchable(),
                ])
                ->columns(2),
    
            // Address Section
            Section::make('Shipping Address')
                ->schema([
                    TextInput::make('region')
                        ->label('Region')
                        ->maxLength(191),
                    TextInput::make('province')
                        ->label('Province')
                        ->maxLength(191),
                    TextInput::make('city_municipality')
                        ->label('City/Municipality')
                        ->maxLength(191),
                    TextInput::make('barangay')
                        ->label('Barangay')
                        ->maxLength(191),
                    TextInput::make('street')
                        ->label('Street')
                        ->maxLength(191),
                    TextInput::make('zip_code')
                        ->label('Zip Code')
                        ->maxLength(191),
                ])
                ->columns(2),
    
            // Payment Details Section
            Section::make('Payment Details')
                ->schema([
                    TextInput::make('payment_method')
                        ->label('Payment Method')
                        ->maxLength(191)
                        ->required(),
                    TextInput::make('payment_reference')
                        ->label('Payment Reference')
                        ->maxLength(191),
                ])
                ->columns(2),
    
            // Order Status Section
            Section::make('Order Status')
                ->schema([
                    TextInput::make('status')
                        ->label('Status')
                        ->required(),
                    DatePicker::make('order_date')
                        ->label('Order Date')
                        ->required()->date(),
                    DatePicker::make('shipped_date')
                        ->label('Shipped Date'),
                    DatePicker::make('delivery_date')
                    ->date()
                        ->label('Delivery Date'),
                ])
                ->columns(2),
    
            // Additional Details Section
            Section::make('Additional Details')
                ->schema([
                    TextInput::make('total')
                        ->label('Total Amount')
                        ->numeric(),
                    Toggle::make('is_received')
                        ->label('Order Received')
                        ->required(),
                ])
                ->columns(2),
        ];
    }
}
