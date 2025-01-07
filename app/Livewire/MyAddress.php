<?php

namespace App\Livewire;

use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Location;
use Filament\Actions\Action;
use App\Services\PSGCService;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class MyAddress extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;

    public $addresses = [];
    public $regions = [];
    public $provinces = [];
    public $cities = [];
    public $barangays = [];
    public $selectedRegion = null;
    public $selectedProvince = null;
    public $selectedCity = null;
    public $name;

    private ?PSGCService $psgcService = null;

    public function mount($name)
    {
        $this->name = $name;

        try {
            $this->regions = $this->getPSGCService()->getRegions();
            $this->addresses = auth()->user()->getAddresses();
        } catch (\Exception $e) {
            $this->handleError('Error loading initial data', $e);
        }
    }

    public function render()
    {
        return view('livewire.my-address');
    }

    public function addAddressAction(): Action
    {
        return Action::make('addAddress')
            ->label('New Address')
            ->modalHeading('Add New Address')
            ->size('xl')
            ->form([
                Select::make('region')
                    ->label('Region')
                    ->options(collect($this->regions)->pluck('name', 'code'))
                    ->required()
                    ->live()
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(fn ($state) => $this->updateProvinces($state)),

                Select::make('province')
                    ->label('Province')
                    ->options(fn () => collect($this->provinces)->pluck('name', 'code'))
                    ->required()
                    ->live()
                    ->searchable()
                    ->preload()
                    ->disabled(function(Get $get){
                        return $get('region') === null;
                    })
                    ->afterStateUpdated(fn ($state) => $this->updateCities($state)),

                Select::make('city')
                    ->label('City/Municipality')
                    ->options(fn () => collect($this->cities)->pluck('name', 'code'))
                    ->required()
                    ->live()
                    ->searchable()
                    ->preload()
                    ->disabled(function(Get $get){
                        return $get('province') === null;
                    })
                    ->afterStateUpdated(fn ($state) => $this->updateBarangays($state)),

                Select::make('barangay')
                    ->label('Barangay')
                    ->disabled(function(Get $get){
                        return $get('province') === null;
                    })
                    ->searchable()
                    ->preload()
                    ->options(fn () => collect($this->barangays)->pluck('name', 'name'))
                    ->required(),

                TextInput::make('street')
                    ->label('Street')
                    ->required(),

                TextInput::make('zip_code')
                    ->label('ZIP Code')
                    ->required()
                    ->numeric()
                    ->mask(9999),
            ])
            ->action(function (array $data) {
                try {
                    DB::beginTransaction();

                    Location::create([
                        'user_id' => auth()->id(),
                        'region' => $data['region'],
                        'province' => $data['province'],
                        'city_municipality' => $data['city'],
                        'barangay' => $data['barangay'],
                        'street' => $data['street'],
                        'zip_code' => $data['zip_code'],
                    ]);

                    DB::commit();

                    $this->addresses = auth()->user()->getAddresses();

                    $this->dialog()->success(
                        title: 'Address Added',
                        description: 'The new address has been successfully added.'
                    );
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->handleError('Error adding address', $e);
                }
            });
    }

    private function updateProvinces($regionCode)
    {
        try {
            $this->provinces = $this->getPSGCService()->getProvinces($regionCode);
            $this->selectedProvince = null;
            $this->cities = [];
            $this->barangays = [];
        } catch (\Exception $e) {
            $this->handleError('Error fetching provinces', $e);
        }
    }

    private function updateCities($provinceCode)
    {
        try {
            $this->cities = $this->getPSGCService()->getCitiesMunicipalities($provinceCode);
            $this->selectedCity = null;
            $this->barangays = [];
        } catch (\Exception $e) {
            $this->handleError('Error fetching cities', $e);
        }
    }

    private function updateBarangays($cityCode)
    {
        try {
            $this->barangays = $this->getPSGCService()->getBarangays($cityCode);
        } catch (\Exception $e) {
            $this->handleError('Error fetching barangays', $e);
        }
    }

    private function getPSGCService(): PSGCService
    {
        if (!$this->psgcService) {
            $this->psgcService = app(PSGCService::class);
        }

        return $this->psgcService;
    }

    private function handleError(string $message, \Exception $e): void
    {
        // Log the error for debugging
        \Log::error("$message: " . $e->getMessage(), ['exception' => $e]);

        // Display an error notification
        FilamentForm::error(
            title: 'Error',
            body: "$message. Please try again later."
        );
    }
}
