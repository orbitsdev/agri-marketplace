<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use WireUi\Traits\WireUiActions;
use Filament\Forms\Contracts\HasForms;
class WaitingForApproval extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;
    public function render()
    {
        return view('livewire.waiting-for-approval');
    }

    
}
