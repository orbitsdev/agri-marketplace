<?php

namespace App\Livewire\Buyer;

use Filament\Forms;
use App\Models\User;
use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

use WireUi\Traits\WireUiActions;
class EditProfile extends Component implements HasForms
{
    use InteractsWithForms;
    use WireUiActions;

    public ?array $data = [];

    public User $record;

public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

public function form(Form $form): Form
    {
        return $form
            ->schema(FilamentForm::profileForm())
            ->statePath('data')
            ->model($this->record);
    }

    public function save()
    {
        $data = $this->form->getState();

        $this->record->update($data);

        $this->dialog()->success(
            title: 'Update Successful',
            description: 'The changes have been saved successfully.'
        );

        // return redirect()->route('dashboard');
    }

    public function render(): View
    {
        return view('livewire.buyer.edit-profile');
    }
}
