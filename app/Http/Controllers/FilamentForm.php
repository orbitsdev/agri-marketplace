<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            ]),


        ];
    }
}
