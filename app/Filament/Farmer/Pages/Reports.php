<?php

namespace App\Filament\Farmer\Pages;

use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.farmer.pages.reports';

    protected static ?int $navigationSort = 4;
}
