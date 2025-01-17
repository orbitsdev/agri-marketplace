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

class AdminForm extends Controller
{
    public static function farm(): array{
        return [
            Select::make('user_id')
            ->relationship(
                name: 'user',
                titleAttribute: 'last_name',
                modifyQueryUsing: fn (Builder $query) => $query->isFarmer(),
            )
            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->fullName}")
            ->searchable(['first_name', 'last_name'])
            ->disabled(function($operation){
                return $operation === 'edit';
            })
            ->preload()
            ,
            TextInput::make('farm_name')
                ->maxLength(191),
            TextInput::make('location')
                ->maxLength(191),
            TextInput::make('farm_size')
                ->maxLength(191),
            Textarea::make('description')
                ->columnSpanFull(),
            Textarea::make('contact')
                ->columnSpanFull(),
            TextInput::make('status')
                ->required(),
            Textarea::make('remarks')
                ->columnSpanFull(),
        ];
    }
}
