<?php

namespace App\Filament\Farmer\Pages;

use App\Filament\Notification;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Mockery\Matcher\Not;

class EditProfile extends  BaseEditProfile
{
    public function getHeading(): string
    {
        return 'My Profile';
    }


public function form(Form $form): Form
{
    return $form
        ->schema([
            ...FilamentForm::profileform(),
        ]);
}

    protected function mutateFormDataBeforeSave(array $data): array
{
   

    return $data;
}


protected function getSaveFormAction(): Action
{
    return Action::make('save')
        ->label('Save Changes')
        ->form([
            TextInput::make('current_password')
                ->label('Enter your current password to confirm')
                ->password()
                ->required(),
        ])
        ->modalHeading('Confirm Profile Update')
        ->modalDescription('For your security, please confirm your current password.')
        ->modalSubmitActionLabel('Confirm and Save')
        ->requiresConfirmation()
        ->action(function (array $data): void {


            if (! Hash::check($data['current_password'], auth()->user()->password)) {
                $this->addError('current_password', 'The current password you entered is incorrect.');

               Notification::make()
               ->title('Error')
               ->body('The current password you entered is incorrect.')
               ->danger()
               ->send();
                return;
            }
        //    Notification::success('Profile updated successfully!')->send();
         $this->save(); //

        // (Optional) show success notification
        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Profile Updated')
            ->body('Your profile has been successfully updated!')
            ->send();
        });
}
}
