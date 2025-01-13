<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FilamentForm;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class PlaceOrder extends Component implements HasForms, HasActions
{

    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;


    public $groupedPendingOrders;

    public function mount()
    {
       $this->refreshOrder();

    }

    public function refreshOrder()
    {

        $this->groupedPendingOrders = Order::where('buyer_id', Auth::id())
        ->where('status', Order::PENDING)
        ->with(['farmer','items.product']) // Eager load the farmer relationship
        ->get()
        ->groupBy(fn($order) => $order->farmer->id)->all(); // Group orders by farmer ID
    }

    public function render()
    {
        return view('livewire.place-order', [
            'groupedPendingOrders' => $this->groupedPendingOrders,
        ]);
    }

    public function removeOrderAction(): Action
    {
        return Action::make('removeOrder')
            ->label('Delete Order')
            ->iconButton()
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
           
            ->modalHeading('Confirm Delete')
            ->modalDescription('Are you sure you want to delete this order including items ? This action cannot be undone.')
            ->action(function (array $arguments) {

                try {
                    $cartId = $arguments['record'];

                    DB::beginTransaction();


                    $order = Order::findOrFail($cartId);


                    $order->delete();


                    $this->refreshOrder();

                    DB::commit();
                
                    $this->dialog()->success(
                        title: 'Order deleted',
                        description: 'The item has been successfully removed from your cart.'
                    );
                } catch (\Exception $e) {
                    DB::rollBack();

                    $this->dialog()->error(
                        title: 'Error',
                        description: 'Failed to remove the item. Please try again.'
                    );
                }
            });
    }

    public function editAddressAction(): EditAction
    {
        return EditAction::make('editAddress')
            ->record(function (array $arguments) {
               return Order::findOrFail($arguments['record']);
            })
            ->fillForm(function(array $arguments){
                $record = Order::find($arguments['record']);
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
}
