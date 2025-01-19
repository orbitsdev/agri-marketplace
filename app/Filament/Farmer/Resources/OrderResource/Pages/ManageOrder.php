<?php

namespace App\Filament\Farmer\Resources\OrderResource\Pages;

use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use App\Http\Controllers\AdminForm;

use App\Filament\Farmer\Resources\OrderResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;


use Filament\Forms\Components\TextInput;

 
class ManageOrder extends Page implements HasForms
{
    use InteractsWithRecord;
    use InteractsWithForms;

    public ?array $data = [];
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.farmer.resources.order-resource.pages.manage-order';
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(AdminForm::manageOrderRequestForm())
            ->statePath('data');
    }


    
}
