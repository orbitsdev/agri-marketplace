<?php

namespace App\Filament\Farmer\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use App\Http\Controllers\AdminForm;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Farmer\Resources\OrderResource;

class ManageOrderRequest extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected ?string $heading = 'Manage Order';
    public function form(Form $form): Form
{
    return $form
        ->schema(AdminForm::manageOrderRequestForm());
}
}
