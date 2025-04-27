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

public function generateAndSendOtp(): void
{
    $user = auth()->user();

    // If there's an active OTP that hasn't expired, don't generate new
    if ($user->otp_code && $user->otp_expires_at && now()->lessThan($user->otp_expires_at)) {
        \Illuminate\Support\Facades\Log::info('OTP still active. No new OTP sent.');
        return;
    }

    // Otherwise, generate new OTP
    $otp = rand(100000, 999999);

    $user->update([
        'otp_code' => $otp,
        'otp_attempts' => 3,
        'otp_expires_at' => now()->addMinutes(5),
    ]);

    app(\App\Services\TeamSSProgramSmsService::class)
        ->sendSms($user->phone, "Your verification code is: {$otp}");

    \Illuminate\Support\Facades\Log::info('New OTP generated and sent: ' . $otp);
}


protected function getSaveFormAction(): Action
{



    return Action::make('save')
        ->label('Save Changes')
        ->form([
            TextInput::make('otp_code')
                ->label('Enter OTP Code')
                ->mask('999999')
                ->required(),
        ])
        ->modalHeading('Confirm Profile Update')
        ->modalDescription('Please enter the OTP code sent to your registered mobile number.')
        ->modalSubmitActionLabel('Confirm and Save')
        ->requiresConfirmation()
        ->beforeFormFilled(function () {
            $this->generateAndSendOtp(); // ✅ Call only when modal opens
        })
        ->action(function (array $data): void {
            $user = auth()->user();
            if ($user->otp_attempts <= 0) {
                Notification::make()
                    ->title('Too Many Attempts')
                    ->body('You have exceeded the maximum number of attempts. Please request a new OTP.')
                    ->danger()
                    ->send();
                $this->addError('otp_code', 'Too many failed attempts. Please request a new OTP.');
                return;
            }

            if (now()->greaterThan($user->otp_expires_at)) {
                Notification::make()
                    ->title('OTP Expired')
                    ->body('Your OTP has expired. Please request a new OTP.')
                    ->warning()
                    ->send();
                $this->addError('otp_code', 'Your OTP has expired. Please request a new OTP.');
                return;
            }

            if ($user->otp_code !== $data['otp_code']) {
                Notification::make()
                    ->title('Invalid OTP')
                    ->body('The OTP code you entered is invalid. Please try again.')
                    ->danger()
                    ->send();
                $this->addError('otp_code', 'Invalid OTP code. Attempts left: ' . $user->otp_attempts);
                $user->decrement('otp_attempts');
                $user->save();
                return;
            }

            $user->update([
                'otp_code' => null,
                'otp_attempts' => 3,
                'otp_expires_at' => null,
            ]);


         $this->save(); //


        \Filament\Notifications\Notification::make()
            ->success()
            ->title('Profile Updated')
            ->body('Your profile has been successfully updated!')
            ->send();
        });
}
}
