<?php

namespace App\Livewire;

use Filament\Forms\Get;
use Livewire\Component;
use App\Models\Location;
use Filament\Actions\Action;
use App\Services\PSGCService;
use Filament\Actions\EditAction;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
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
            ->form(FilamentForm::locationForm())
            ->action(function (array $data) {
                DB::beginTransaction();

                try {
                    if ($data['is_default']) {
                        Location::where('user_id', auth()->id())->where('is_default', true)->update(['is_default' => false]);
                    }

                    // Fetch the selected region, province, city, and barangay data
                    // $region = collect($this->regions)->firstWhere('code', $data['region']);
                    // $province = collect($this->provinces)->firstWhere('code', $data['province']);
                    // $city = collect($this->cities)->firstWhere('code', $data['city']);
                    // $barangay = collect($this->barangays)->firstWhere('code', $data['barangay']);

                    // Create the new address
                    Location::create([
                        'user_id' => auth()->id(),
                        'region' => $data['region'] ?? null,
                        // 'region_code' => $region['code'] ?? null,
                        'province' => $data['province'] ?? null,
                        // 'province_code' => $province['code'] ?? null,
                        'city_municipality' => $data['city_municipality'] ?? null,
                        // 'city_code' => $city['code'] ?? null,
                        'barangay' => $data['barangay'] ?? null,
                        // 'barangay_code' => $barangay['code'] ?? null,
                        'street' => $data['street'],
                        'zip_code' => $data['zip_code'],
                        'is_default' => $data['is_default'],
                    ]);
                    // Location::create([
                    //     'user_id' => auth()->id(),
                    //     'region' => $region['name'] ?? null,
                    //     'region_code' => $region['code'] ?? null,
                    //     'province' => $province['name'] ?? null,
                    //     'province_code' => $province['code'] ?? null,
                    //     'city_municipality' => $city['name'] ?? null,
                    //     'city_code' => $city['code'] ?? null,
                    //     'barangay' => $barangay['name'] ?? null,
                    //     'barangay_code' => $barangay['code'] ?? null,
                    //     'street' => $data['street'],
                    //     'zip_code' => $data['zip_code'],
                    //     'is_default' => $data['is_default'],
                    // ]);

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
    public function editAddressAction(): EditAction
    {
        return EditAction::make('editAddress')
            ->record(function (array $arguments) {
               return Location::findOrFail($arguments['record']);
            })
            ->fillForm(function(array $arguments){
                $record = Location::find($arguments['record']);
                return [
                    'region'=> $record->region,
                    'province'=> $record->province,
                    'city_municipality'=> $record->city_municipality,
                    'barangay'=> $record->barangay,
                    'street'=> $record->street,
                    'zip_code'=> $record->zip_code,
                    'is_default'=> $record->is_default,
                ];
            })
            ->using(function (Model $record, array $data): Model {
                if ($data['is_default']) {
                    // Update existing default address for the user
                    Location::where('user_id', auth()->id())
                        ->where('is_default', true)
                        ->where('id', '!=', $record->id)
                        ->update(['is_default' => false]);
                }
                $record->update($data);

                return $record;
            })
            ->icon('heroicon-m-pencil-square')
             ->iconButton()
            ->form(FilamentForm::locationForm())
            ->label('New Address')
            ->modalHeading('Add New Address')
            ->size('xl')


              ;
    }

    public function deleteLocationAction(): Action
    {
        return Action::make('deleteLocation')
            // ->size(ActionSize::Small)
            ->iconButton()
            ->icon('heroicon-m-x-mark')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                DB::beginTransaction();

                try {
                    $location = Location::findOrFail($arguments['record']);

                    // Check the total number of locations for the user
                    $totalLocations = Location::where('user_id', auth()->id())->count();

                    if ($totalLocations > 1) {
                        $location->delete();

                        // Check if there is only one location left after deletion
                        $remainingLocations = Location::where('user_id', auth()->id())->get();

                        if ($remainingLocations->count() === 1) {
                            $remainingLocations->first()->update(['is_default' => true]);
                        }

                        FilamentForm::success('Location deleted successfully.');
                    } else {
                        FilamentForm::error('Cannot delete the only remaining location.');
                    }

                    DB::commit();

                    // Refresh the addresses
                    $this->addresses = auth()->user()->getAddresses();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->handleError('Error deleting location', $e);
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
