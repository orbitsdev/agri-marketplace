<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Pages\Auth\Register;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Wizard;
use App\Http\Controllers\FilamentForm;
use Illuminate\Auth\Events\Registered;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class RegisterFarmer extends Register
{

    public function form(Form $form): Form
    {

        return $form->schema([

            Wizard::make([
                Wizard\Step::make('Account Details')
                    ->schema([
                        TextInput::make('first_name')
                            ->required(),

                        TextInput::make('middle_name')
                            ->required(),

                        TextInput::make('last_name')
                            ->required(),


                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),
                Wizard\Step::make('Farm Details')
                    ->schema([
                        Group::make()
                        ->columnSpanFull()
                        ->relationship('farmer')
                        ->schema([
                            TextInput::make('farm_name')
                            ->required(),
                            TextInput::make('contact')
                            ->label('Contact Number')
                            ->mask(99999999999)
                         

                            ->tel()
                            ->maxLength(191),
                        TextInput::make('location')
                            ->required(),
                        TextInput::make('farm_size')
                            ->required(),
                        RichEditor::make('description')
                            ->columnSpanFull()
                            ->required()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                        ]),
                       
                    ]),
                Wizard\Step::make('Farm Documents')
                    ->schema([
                        Group::make()

                            ->columnSpanFull()
                            ->relationship('farmer')

                            ->schema([
                                TableRepeater::make('farmer_documents')
                                    ->relationship('documents')
                                    ->columnSpanFull()
                                    ->columnWidths([

                                        'name' => '200px',
                                    ])
                                    ->maxItems(6)

                                    ->withoutheader()
                                    ->schema([

                                        // text input name
                                        TextInput::make('name')
                                            ->required(),

                                        SpatieMediaLibraryFileUpload::make('file')


                                    ]),

                            ])->columnSpanFull(),
                    ]),
            ]),

        ])
        ->model(User::class)
        ->statePath('data');

        // return $form->schema(FilamentForm::userForm())->statePath('data');
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        $data['role'] = User::FARMER;


        $user = $this->getUserModel()::create($data);

        app()->bind(
            \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
            // \Filament\Listeners\Auth\SendEmailVerificationNotification::class,
        );
        event(new Registered($user));

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
