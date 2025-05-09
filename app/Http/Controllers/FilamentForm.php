<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use Illuminate\Http\Request;
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

class FilamentForm extends Controller
{
    public static function profileform()
    {
        return [


            Wizard::make([
                Wizard\Step::make('Account Details')
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->columnSpan(
                                [
                                    'sm' => 2,
                                    'md' => 4,
                                    'lg' => 4,
                                ]
                            ),
                        TextInput::make('middle_name')
                            ->required()
                            ->columnSpan(
                                [
                                    'sm' => 2,
                                    'md' => 4,
                                    'lg' => 4,
                                ]
                            ),
                        TextInput::make('last_name')
                            ->required()
                            ->columnSpan(
                                [
                                    'sm' => 2,
                                    'md' => 4,
                                    'lg' => 4,
                                ]
                            ),

                        TextInput::make('email')
                            ->required()
                            ->disabled(fn(string $operation): bool => $operation === 'App\Livewire\Buyer\EditProfile')
                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ]),


                        TextInput::make('phone')
                            ->prefix('+63')
                            ->mask('9999999999')
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ]),

                        Select::make('role')
                            ->default(User::FARMER)
                            ->required()
                            ->options(User::ROLE_OPTIONS)
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ])
                            ->searchable()
                            ->live()



                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ])
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->label(fn(string $operation) => $operation == 'create' ? 'Password' : 'New Password')
                            ->helperText('Your password will be updated. Make sure to save it in a secure place before proceeding.'),

                        SpatieMediaLibraryFileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull()
                            ->label('Profile'),


                    ]),
                Wizard\Step::make('Farm Details')
                    ->schema([
                        ...self::farmerForm(),
                    ])->hidden(function (Get $get) {
                        return $get('role') !== User::FARMER;
                    }),
                Wizard\Step::make('Application Requirements')
                    ->schema([
                        ...self::farmRequiredDocuments(),
                        // ...self::farmDocuments(),
                    ])->hidden(function (Get $get) {
                        return $get('role') !== User::FARMER;
                    }),
            ])
                ->skippable()
                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->columnSpanFull(),



        ];
    }
    public static function userForm()
    {
        return [


            Wizard::make([
                Wizard\Step::make('User Details')
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->columnSpan(
                                [
                                    'sm' => 2,
                                    'md' => 4,
                                    'lg' => 4,
                                ]
                            ),
                        TextInput::make('middle_name')
                            ->required()
                            ->columnSpan(
                                [
                                    'sm' => 2,
                                    'md' => 4,
                                    'lg' => 4,
                                ]
                            ),
                        TextInput::make('last_name')
                            ->required()
                            ->columnSpan(
                                [
                                    'sm' => 2,
                                    'md' => 4,
                                    'lg' => 4,
                                ]
                            ),

                        TextInput::make('email')
                            ->required()

                            ->unique(ignoreRecord: true)
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ]),


                        TextInput::make('phone')
                            ->prefix('+63')
                            ->mask('9999999999')
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ]),

                        Select::make('role')
                            ->default(User::FARMER)
                            ->required()
                            ->options(User::ROLE_OPTIONS)
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ])
                            ->searchable()
                            ->live()


                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->columnSpan([
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ])
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->label(fn(string $operation) => $operation == 'create' ? 'Password' : 'New Password'),

                        FileUpload::make('profile_photo_path')
                            ->disk('public')
                            ->directory('accounts')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull()
                            ->label('Profile'),
                    ]),
                Wizard\Step::make('Farm Details')
                    ->schema([
                        ...self::farmerForm(),
                    ])->hidden(function (Get $get) {
                        return $get('role') !== User::FARMER;
                    }),
                Wizard\Step::make('Farm Documents')
                    ->schema([
                        ...self::farmRequiredDocuments()
                        // ...self::farmDocuments(),
                    ])->hidden(function (Get $get) {
                        return $get('role') !== User::FARMER;
                    }),
            ])

                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->columnSpanFull(),



        ];
    }



    public static function farmerForm()
    {
        return [
            Section::make('Farm Information')
                ->description('Provide details about your farm')
                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->columnSpanFull()
                ->relationship('farmer')
                ->schema([
                    TextInput::make('farm_name')
                        ->label('Farm Name')
                        ->placeholder('Enter your farm name')
                        ->required()
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    TextInput::make('location')
                        ->label('Farm Location')
                        ->placeholder('Enter farm location')
                        ->required()
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    TextInput::make('farm_size')
                        ->label('Farm Size (hectares)')
                        ->placeholder('Enter farm size')
                        ->required()
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    RichEditor::make('description')
                        ->label('Farm Description')
                        ->placeholder('Describe your farm, including crops grown, farming methods, etc.')
                        ->columnSpanFull()
                        ->required()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'bulletList',
                            'orderedList',
                            'link',
                        ]),
                ]),
        ];
    }
    public static function farmRequiredDocuments()
    {
        return [
            Group::make()
                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->columnSpanFull()
                ->relationship('farmer')

                ->schema([
                    TableRepeater::make('farmer_requirments_documents')
                        ->relationship('farmer_requirements')
                        ->maxItems(10)
                        ->columnSpanFull()
                        ->columnWidths([
                            'name' => '200px',
                            'file' => '500px',
                        ])
                        ->addable(false)
                        ->deletable(false)

                        ->withoutheader()
                        ->schema([
                            Select::make('requirement_id')
                                ->label('Doc Type')
                                ->relationship(
                                    'requirement',
                                    'name'
                                )
                                ->disabled()
                                        ->dehydrated(false)
                                        ->preload(),
                            SpatieMediaLibraryFileUpload::make('file')

                                ->label('Upload')

                        ]),

                ])->columnSpanFull(),



        ];
    }
    public static function farmDocuments()
    {
        return [
            Group::make()
                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->columnSpanFull()
                ->relationship('farmer')

                ->schema([
                    TableRepeater::make('farmer_documents')
                        ->relationship('documents')
                        ->maxItems(10)
                        ->columnSpanFull()
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
                            //spatied media library file upload
                            // SpatieMediaLibraryFileUpload::make('files')
                            // ->multiple()
                            // ->reorderable()->maxFiles(3)

                        ]),

                ])->columnSpanFull(),



        ];
    }

    public static function farmerDetailsForm(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Personal Details')
                    ->schema([
                        TextInput::make('first_name')
                        ->required()
                        ->columnSpan(
                            [
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ]
                        ),
                    TextInput::make('middle_name')
                        ->required()
                        ->columnSpan(
                            [
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ]
                        ),
                    TextInput::make('last_name')
                        ->required()
                        ->columnSpan(
                            [
                                'sm' => 2,
                                'md' => 4,
                                'lg' => 4,
                            ]
                        ),
                    TextInput::make('phone')
                    ->prefix('+63')
                    ->mask('9999999999')->required(),
                    ]),
                Wizard\Step::make('Farm Details')
                    ->schema(self::farmerForm()),

                Wizard\Step::make('Farm Documents')
                    ->schema(self::farmRequiredDocuments()),

            ])->skippable()

        ];
    }

    public static function productForm()
    {
        return [
            Section::make('Product Details')
                ->description('Provide the necessary product details. Ensure all required fields are filled out correctly.')
                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->columnSpanFull()
                ->schema([
                    // Product Image
                    SpatieMediaLibraryFileUpload::make('image')
                        ->columnSpanFull()
                        ->label('Product Image')
                        ->image()
                        ->imageEditor()
                        ->required(),

                    // Product Name
                    TextInput::make('product_name')
                        ->required()
                        ->label('Product Name')
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 12,
                        ]),


                    // Quantity
                    TextInput::make('quantity')
                        ->required()
                        ->numeric()
                        ->default(1)
                        ->label('Quantity')
                        ->helperText('Enter the number of items available in stock.')
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    // Category Selection
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->label('Product Category')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->helperText('Select the appropriate category for this product.')
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    // Price
                    TextInput::make('price')
                        ->required()
                        ->numeric()
                        ->label('Price')
                        ->prefix('₱')
                        ->helperText('Enter the price for a single unit.')
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    TextInput::make('short_description')
                        ->required()
                        ->label('Short Description')
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 12,
                        ]),
                    // Description
                    Textarea::make('description')
                        ->required()
                        ->label('Product Description')
                        ->columnSpanFull()
                        ->helperText('Provide a detailed description of the product.')
                        ->rows(5),
                    // ->toolbarButtons([
                    //     'attachFiles',
                    //     'blockquote',
                    //     'bold',
                    //     'bulletList',
                    //     'codeBlock',
                    //     'h2',
                    //     'h3',
                    //     'italic',
                    //     'link',
                    //     'orderedList',
                    //     'redo',
                    //     'strike',
                    //     'underline',
                    //     'undo',
                    // ]),

                    // Status
                    Select::make('status')
                        ->options(Product::STATUS_OPTIONS)
                        ->default('Available')
                        ->required()
                        ->label('Product Status')
                        ->helperText('Set the current availability status of the product.')
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    // Stock Alert Level
                    TextInput::make('alert_level')
                        ->label('Stock Alert Level')
                        ->numeric()
                        ->default(20)
                        ->helperText('Set the minimum stock level for triggering alerts.')
                        ->required()
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    // Publish Toggle
                    Toggle::make('is_published')
                        ->required()
                        ->label('Publish')
                        ->helperText('Toggle to make this product visible to buyers.')
                        ->columnSpanFull(),
                ]),
        ];
    }


    public static function locationForm(): array
    {
        return [


            TextInput::make('region')
                ->label('Region')
                ->placeholder('Enter Region')
                ->required(),

            TextInput::make('province')
                ->label('Province')
                ->placeholder('Enter Province')
                ->required(),

            TextInput::make('city_municipality')
                ->label('City/Municipality')
                ->placeholder('Enter City/Municipality')
                ->required(),

            TextInput::make('barangay')
                ->label('Barangay')
                ->placeholder('Enter Barangay')
                ->required(),


            TextInput::make('street')
                ->label('Street')
                ->required(),

            TextInput::make('zip_code')
                ->label('ZIP Code')
                ->required()
                ->numeric()
                ->mask(9999),


            TextInput::make('phone')
                ->prefix('+63')
                ->mask('9999999999'),
            Toggle::make('is_default')->default(true)
        ];
    }

    public static function orderForm(): array
    {
        return [


            TextInput::make('region')
                ->label('Region')
                ->placeholder('Enter Region')
                ->required(),

            TextInput::make('province')
                ->label('Province')
                ->placeholder('Enter Province')
                ->required(),

            TextInput::make('city_municipality')
                ->label('City/Municipality')
                ->placeholder('Enter City/Municipality')
                ->required(),

            TextInput::make('barangay')
                ->label('Barangay')
                ->placeholder('Enter Barangay')
                ->required(),


            TextInput::make('street')
                ->label('Street')
                ->required(),

            TextInput::make('zip_code')
                ->label('ZIP Code')
                ->required()
                ->numeric()
                ->mask(9999),

            // Select

            Select::make('payment_method')
                ->options(Order::PAYMENT_METHOD_OPTIONS)

        ];
    }


    public static function success(String $title = 'Success', String $body = null)
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->success()
            ->send();
    }

    public static function danger(String $title = 'Success', String $body = null)
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->danger()
            ->send();
    }
    public static function error(String $title = 'Success', String $body = null)
    {
        Notification::make()
            ->title($title)
            ->body($body)
            ->danger()
            ->send();
    }
}
