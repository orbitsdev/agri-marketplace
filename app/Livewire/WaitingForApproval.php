<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Group;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Wizard;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class WaitingForApproval extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;

    public $status;
    public $remarks;
    public function render()
    {

        $user = Auth::user();

        // Ensure the user is a farmer and not approved
        if (!$user || !$user->isFarmer() || $user->farmer->status === 'Approved') {
            abort(403, 'Unauthorized');
        }

        $this->status = $user->farmer->status;
        $this->remarks = $user->farmer->remarks;
        return view('livewire.waiting-for-approval');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // filam

    public function updateFarmDetailsAction(): EditAction
    {
        return EditAction::make('updateFarmDetailsAction')
            ->record(function (array $arguments) {
               return User::findOrFail($arguments['record']);
            })
            ->iconButton()
            ->model(User::class)
            ->using(function (Model $record, array $data): Model {

                $record->update($data);

                return $record;
            })
            ->label('Update Farmer Details')
            ->icon('heroicon-m-pencil-square')
            ->form(FilamentForm::farmerDetailsForm())
            ->label('Update Farm Details')
            ->modalHeading('Update Farm Details')
            ->modalWidth(MaxWidth::SevenExtraLarge)




              ;
    }


}
