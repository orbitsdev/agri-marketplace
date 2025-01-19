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
use Filament\Forms\Components\Hidden;
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
    public static function manageFarmForm(): array
    {
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

    public static function orderForm(): array
    {
        return [
            Wizard::make()
                ->schema([
                    // Step 1: Order Details
                    Wizard\Step::make('Order Details')
                        ->schema([
                            Select::make('buyer_id')
                                ->label('Buyer')
                                ->relationship(
                                    'buyer',
                                    'id',
                                    modifyQueryUsing: fn(Builder $query) => $query->isBuyers(),
                                )
                                ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->fullName} - ")
                                ->searchable(['first_name', 'last_name', 'email'])
                                ->preload()
                                ->disabled(function ($operation) {
                                    return $operation === 'edit';
                                })
                                ->required(),
                            TextInput::make('order_number')
                                ->disabled(fn($operation) => $operation === 'edit')
                                ->hidden(fn($operation) => $operation === 'create')
                                ->required()
                                ->maxLength(191),
                        ])
                        ->columns(2),

                    Wizard\Step::make('Order Items')
                        ->schema([
                            TableRepeater::make('order_items_list')
                                ->columnWidths([
                                    'quantity' => '300px',
                                ])
                                ->relationship('items')
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
                                        ->relationship(
                                            'product',
                                            'id',
                                            modifyQueryUsing: fn(Builder $query) => $query,
                                        )
                                        ->distinct()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->product_name}")
                                        ->searchable(['product_name', 'short_description'])
                                        ->preload()
                                        ->required(),
                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required(),
                                ])
                                ->withoutHeader()
                                ->columnSpan('full')
                                ->label('Items')
                                ->maxItems(6),
                        ])

                        ->columns(1),

                    // Step 2: Shipping Address
                    Wizard\Step::make('Shipping Address')
                        ->schema([
                            TextInput::make('region')
                                ->label('Region')
                                ->required()
                                ->maxLength(191),
                            TextInput::make('province')
                                ->label('Province')
                                ->required()
                                ->maxLength(191),
                            TextInput::make('city_municipality')
                                ->label('City/Municipality')
                                ->required()
                                ->maxLength(191),
                            TextInput::make('barangay')
                                ->label('Barangay')
                                ->required()
                                ->maxLength(191),
                            TextInput::make('street')
                                ->label('Street')
                                ->required()
                                ->maxLength(191),
                            TextInput::make('zip_code')
                                ->label('Zip Code')
                                ->required()
                                ->maxLength(191),
                        ])
                        ->columns(2),


                    // Step 3: Order Status
                    Wizard\Step::make('Order Status')
                        ->schema([
                            Select::make('status')
                                ->label('Status')
                                ->options(Order::STATUS_OPTIONS)
                                ->required()
                                ->placeholder('Select a status'),
                            DatePicker::make('order_date')
                                ->label('Order Date')
                                ->date()
                                ->required(),
                            // DatePicker::make('shipped_date')
                            //     ->label('Shipped Date')
                            //     ->date()
                            //     ->required(),
                            // DatePicker::make('delivery_date')
                            //     ->label('Delivery Date')
                            //     ->date()
                            //     ->required(),
                        ])
                        ->columns(2),

                    // Step 4: Payment Details
                    Wizard\Step::make('Payment Details')
                        ->schema([
                            Select::make('payment_method')
                                ->label('Payment Method')
                                ->options(Order::PAYMENT_METHOD_OPTIONS)
                                ->required()
                                ->placeholder('Select a payment method'),
                            TextInput::make('payment_reference')
                                ->label('Payment Reference')
                                ->maxLength(191),
                        ])
                        ->columns(2),

                    // Step 5: Order Items

                ])->columnSpanfull()


        ];
    }
    public static function manageOrderRequestForm(): array
    {
        return [
            Section::make('Order Status')
                ->description('Manage the order status and provide relevant details for any changes.')
                ->schema([
                    Select::make('status')
                        ->label('Status')
                        ->options(function (Model $record) {
                            return $record->getAvailableStatusTransitions();
                        })
                        ->live(debounce:500)
                        ->required(function(Model $record){
                            return  empty($record->status) ? true :false;
                        })
                        ->columnSpanFull()
                        ->placeholder('Select a status'),
                        

                    // DatePicker::make('order_date')
                    //     ->label('Order Date')
                    //     ->required()->date(),
                    DatePicker::make('shipped_date')
                        ->label('Shipped Date')
                        ->required(),
                    DatePicker::make('delivery_date')
                        ->date()
                        ->required()
                        ->label('Delivery Date'),
                    Textarea::make('remarks')
                        ->label('Why?')
                        ->helperText('Kindly provide a reason for your decision')
                        ->columnSpanFull()
                        ->required()
                        ->rows(5)
                        ->hidden(function (Get $get) {
        
                            return !in_array($get('status'), [Order::CANCELLED, Order::RETURNED]);
                        })
                        ,
                       
                        Toggle::make('is_received')
                        ->required()->label('Order Received'),

                        TableRepeater::make('Order Movement')
                        ->relationship('orderMovements') // Ensure this matches the relationship in your Order model
                        ->columnSpanFull()
                       
                        ->maxItems(10) // Restrict to a maximum of 10 movements
                        ->withoutHeader() // Remove the table header for a cleaner UI
                        ->schema([
                            TextInput::make('current_location')
                                ->label('Current Location')
                                ->required()
                                ->maxLength(191),
                    
                            TextInput::make('destination')
                                ->label('Destination')
                                ->required()
                                ->maxLength(191),
                    
                        
                           
                        
                            ])->hidden(function (Get $get) {
        
                                return !in_array($get('status'), [Order::OUT_FOR_DELIVERY, Order::COMPLETED]);
                            })

                ])
                ->columns(2),


        ];
    }
}
