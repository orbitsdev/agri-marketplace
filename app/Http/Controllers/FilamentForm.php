<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Forms\Get;
use Illuminate\Http\Request;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class FilamentForm extends Controller
{
    public static function userForm()
    {
        return [
            Section::make('Account Details')
                ->description('Provide the necessary account details. Ensure all required fields are filled out correctly.')
                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 6,
                            'xl' => 8,
                            '2xl' => 12,
                        ]),

                    TextInput::make('email')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ]),

                    Select::make('role')
                        ->default(User::BUYER)
                        ->required()
                        ->options(User::ROLE_OPTIONS)
                        ->columnSpan([
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 4,
                        ])
                        ->searchable()
                        ->live()
                        ->hidden(fn(string $operation): bool => $operation === 'edit'),

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

                        ...self::farmerForm(),
                ]),


        ];
    }

    public static function farmerForm()
    {
        return [

            Group::make()
            ->hidden(function (Get $get) {
                return $get('role') !== User::FARMER;
            })
            ->relationship('farmer')
            ->columnSpanFull()
            ->schema([



            Section::make('Farmer Details')
                ->columns([
                    'sm' => 2,
                    'md' => 4,
                    'lg' => 6,
                    'xl' => 8,
                    '2xl' => 12,
                ])
                ->description('Provide the necessary farmer details. Ensure all required fields are filled out correctly.')->schema([
                // text input farmer name
                TextInput::make('farmer_name')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                        'xl' => 8,
                        '2xl' => 12,
                    ]),
                // text input location
                TextInput::make('location')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                        'xl' => 8,
                        '2xl' => 12,
                    ]),
                // text input farm size
                TextInput::make('farm_size')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                        'xl' => 8,
                        '2xl' => 12,
                    ]),
                // text input description
                TextInput::make('description')
                    ->required()
                    ->columnSpan([
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                        'xl' => 8,
                        '2xl' => 12,

                ]),

              

            ]),

            

            ])

            

        ];
    }
}
