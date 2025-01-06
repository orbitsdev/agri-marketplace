<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Filament\Forms\Get;
use Illuminate\Http\Request;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class FilamentForm extends Controller
{
    public static function userForm()
    {
        return [


            Wizard::make([
                Wizard\Step::make('User Details')
                    ->schema([
                        TextInput::make('first_name')
                                            ->required()
                                            ->columnSpan([
                                                'sm' => 2,
                                                'md' => 4,
                                                'lg' => 4,
                                            ]
                                        ),
                                        TextInput::make('middle_name')
                                            ->required()
                                            ->columnSpan([
                                                'sm' => 2,
                                                'md' => 4,
                                                'lg' => 4,
                                            ]
                                        ),
                                        TextInput::make('last_name')
                                            ->required()
                                            ->columnSpan([
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
                        ...self::farmDocuments(),
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

    //         Tabs::make('User Management')
    //         ->columnSpanFull()
    // ->tabs([
    //     Tabs\Tab::make('Account Details')
    //         ->schema([
    //             Section::make('Account Details')
    //             ->description('Provide the necessary account details. Ensure all required fields are filled out correctly.')
    //             ->columns([
    //                 'sm' => 2,
    //                 'md' => 4,
    //                 'lg' => 6,
    //                 'xl' => 8,
    //                 '2xl' => 12,
    //             ])
    //             ->schema([
    //                 TextInput::make('first_name')
    //                     ->required()
    //                     ->columnSpan([
    //                         'sm' => 2,
    //                         'md' => 4,
    //                         'lg' => 4,
    //                     ]
    //                 ),
    //                 TextInput::make('middle_name')
    //                     ->required()
    //                     ->columnSpan([
    //                         'sm' => 2,
    //                         'md' => 4,
    //                         'lg' => 4,
    //                     ]
    //                 ),
    //                 TextInput::make('last_name')
    //                     ->required()
    //                     ->columnSpan([
    //                         'sm' => 2,
    //                         'md' => 4,
    //                         'lg' => 4,
    //                     ]
    //                 ),

    //                 TextInput::make('email')
    //                     ->required()
    //                     ->unique(ignoreRecord: true)
    //                     ->columnSpan([
    //                         'sm' => 2,
    //                         'md' => 4,
    //                         'lg' => 4,
    //                     ]),

    //                 Select::make('role')
    //                     ->default(User::FARMER)
    //                     ->required()
    //                     ->options(User::ROLE_OPTIONS)
    //                     ->columnSpan([
    //                         'sm' => 2,
    //                         'md' => 4,
    //                         'lg' => 4,
    //                     ])
    //                     ->searchable()
    //                     ->live()
    //                     ->hidden(fn(string $operation): bool => $operation === 'edit'),

    //                 TextInput::make('password')
    //                     ->password()
    //                     ->revealable()
    //                     ->columnSpan([
    //                         'sm' => 2,
    //                         'md' => 4,
    //                         'lg' => 4,
    //                     ])
    //                     ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
    //                     ->dehydrated(fn(?string $state): bool => filled($state))
    //                     ->required(fn(string $operation): bool => $operation === 'create')
    //                     ->label(fn(string $operation) => $operation == 'create' ? 'Password' : 'New Password'),

    //                 FileUpload::make('profile_photo_path')
    //                     ->disk('public')
    //                     ->directory('accounts')
    //                     ->image()
    //                     ->imageEditor()
    //                     ->columnSpanFull()
    //                     ->label('Profile'),




    //             ]),

    //         ]),
    //     Tabs\Tab::make('Farm Details')
    //         ->schema([
    //              ...self::farmerForm(),
    //         ])->hidden(function (Get $get) {
    //             return $get('role') !== User::FARMER;
    //         }),
    //     Tabs\Tab::make('Farm Documents')
    //         ->schema([
    //         ...self::farmDocuments(),
    //         ])->hidden(function (Get $get) {
    //             return $get('role') !== User::FARMER;
    //         }),
    //     ]),


        ];
    }

    public static function farmerForm()
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
    ]),



            // Group::make()
            // ->hidden(function (Get $get) {
            //     return $get('role') !== User::FARMER;
            // })
            // //columns
            // ->columns([
            //     'sm' => 2,
            //     'md' => 4,
            //     'lg' => 6,
            //     'xl' => 8,
            //     '2xl' => 12,
            // ])
            // ->columnSpanFull()
            // ->relationship('farmer')

            // ->schema([

            //     Tabs::make('Farmer Details')
            //     //columns
            //     ->columns([
            //         'sm' => 2,
            //         'md' => 4,
            //         'lg' => 6,
            //         'xl' => 8,
            //         '2xl' => 12,
            //     ])
            //     ->tabs([
            //         Tab::make('Farmer Details')
            //             ->schema([

            //             ]),
            //         Tab::make('Documents')
            //             ->schema([

            //     // table repeater documents


            //     TableRepeater::make('farmer_documents')
            //     ->relationship('documents')
            //     ->columnSpanFull()
            //     ->columnWidths([

            //         'name' => '200px',
            //     ])
            //     ->maxItems(3)

            //     ->withoutheader()
            //     ->schema([

            //         // text input name
            //         TextInput::make('name')
            //             ->required()
            //             ->columnSpan([
            //                 'sm' => 2,
            //                 'md' => 4,
            //                 'lg' => 6,
            //                 'xl' => 8,
            //                 '2xl' => 12,
            //             ]),

            //             //spatied media library file upload
            //             SpatieMediaLibraryFileUpload::make('files')
            //             ->multiple()
            //             ->reorderable()->maxFiles(3)

            //     ]),

            //             ]),

            //         ]) ->columnSpanFull(),







            // ])



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
                        SpatieMediaLibraryFileUpload::make('file')
                        //spatied media library file upload
                        // SpatieMediaLibraryFileUpload::make('files')
                        // ->multiple()
                        // ->reorderable()->maxFiles(3)

                ]),

                        ])->columnSpanFull(),



        ];
    }

    public static function productForm()
    {
        return [
            Section::make('Product Details')
            //description
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
                TextInput::make('product_name')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 4,
                    ]),
                TextInput::make('quantity')
                    ->required()
                    //number only
                    ->numeric()
                    ->default(1)
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 4,
                    ]),
                TextInput::make('price')
                    ->required()
                    //number only
                    ->numeric()

                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 4,
                    ]),
                RichEditor::make('description')
                    ->required()
                    ->columnSpanFull()
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
// status

                Select::make('status')
                    ->options(Product::STATUS_OPTIONS)
                    ->default('Available')
                    ->required()
                    //columnspan
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 4,
                    ]),

                    SpatieMediaLibraryFileUpload::make('image')->columnSpanFull() ->image()
                    ->imageEditor()
                    // ->required()
                    ,
            ]),
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
}
