<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Filament\Actions\Action;
use App\Filament\Notification;
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



    /**
     * Submit the farmer application for review
     * Changes status from Draft to Pending
     */


    // filam

    public function updateFarmDetailsAction(): EditAction
    {
        return EditAction::make('updateFarmDetailsAction')
            ->record(function (array $arguments) {
               return User::findOrFail($arguments['record']);
            })
            ->outlined()
            // ->iconButton()
            ->model(User::class)
            ->using(function (Model $record, array $data): Model {
                // Update user data
                $record->update($data);

                // If user is a farmer and in Draft or Rejected status, change to Pending
                if ($record->isFarmer() && $record->farmer &&
                    ($record->farmer->status === 'Draft' || $record->farmer->status === 'Rejected')) {
                    $record->farmer->status = 'Pending';
                    $record->farmer->save();
                }

                // Notify SuperAdmin about the new farmer registration
                $superAdmin = User::where('role', 'Admin')->first();
                if ($superAdmin) {
                    Notification::make()
                        ->title("New Farmer Application - {$record->fullName}")
                        ->body("A farmer has submitted their application for review.")
                        ->sendToDatabase($superAdmin, isEventDispatched: true);
                }

                return $record;
            })
            ->requiresConfirmation()
            ->label('Update & Submit')
            ->icon('heroicon-m-paper-airplane')
            ->form(FilamentForm::farmerDetailsForm())
            ->modalHeading('Submit Farm Registration')
            ->modalDescription('Please complete your farm details and upload all required documents. Submitting this form will send your application for review.')
            ->modalSubmitActionLabel('Submit for Review')
            ->modalWidth(MaxWidth::SevenExtraLarge);
    }

    public function cancelApplicationAction(): Action
    {
        return Action::make('cancelApplicationAction')
            ->label('Cancel Application')
            ->icon('heroicon-o-x-mark')
            ->color('gray')
            ->outlined()
            ->requiresConfirmation()
            ->modalHeading('Cancel Application')
            ->modalDescription('Are you sure you want to cancel your application? This will return it to draft status so you can make changes.')
            ->modalSubmitActionLabel('Yes, Cancel Application')
            ->action(function () {
                $user = Auth::user();

                if (!$user || !$user->isFarmer()) {
                    $this->notification()->error(
                        title: 'Error',
                        description: 'You must be logged in as a farmer to perform this action.'
                    );
                    return;
                }

                $farmer = $user->farmer;

                // Check if the farmer is in Pending status
                if ($farmer->status !== 'Pending') {
                    $this->notification()->error(
                        title: 'Invalid Status',
                        description: 'Only applications in Pending status can be cancelled.'
                    );
                    return;
                }

                // Update the status to Draft
                $farmer->status = 'Draft';
                $farmer->save();

                // Notify the user
                $this->notification()->success(
                    title: 'Application Cancelled',
                    description: 'Your application has been returned to draft status. You can make changes and resubmit when ready.'
                );

                // Refresh the page to show the new status
                $this->redirect(request()->header('Referer'));
            });
    }


}
