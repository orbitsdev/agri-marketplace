<?php

namespace App\Filament\Farmer\Pages;

use App\Http\Controllers\FilamentForm;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithRecord;

class EditProfile extends Page implements HasForms
{
    use InteractsWithRecord;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.farmer.pages.edit-profile';
    protected static bool $shouldRegisterNavigation = false;



    // public function mount(): void
    // {


    //     $this->form->fill();
    // }

    // public ?array $data = [];


    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema(FilamentForm::profileform())
    //         ->statePath('data');
    // }
    // public function mount(int | string $record): void
    // {
    //     $this->record = $this->resolveRecord($record);
    // }




}
