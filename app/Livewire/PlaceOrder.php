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


    public $groupedProcessing;

    public function mount()
    {
       $this->refreshOrder();

    }

    public function refreshOrder()
    {

        $this->groupedProcessing = Order::where('buyer_id', Auth::id())
        ->where('status', Order::PROCESSING)
        ->with(['farmer','items.product']) // Eager load the farmer relationship
        ->get()
        ->groupBy(fn($order) => $order->farmer->id)->all(); // Group orders by farmer ID
    }

    public function render()
    {
        return view('livewire.place-order', [
            'groupedProcessing' => $this->groupedProcessing,
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
        ->iconButton()
            ->color('gray')
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
                    'payment_method'=> $record->payment_method,
                ];
            })
            ->using(function (array $arguments, array $data): Model {

                try {
                    $record = Order::findOrFail($arguments['record']); // It's better to use findOrFail to throw an exception if not found
                
                    DB::beginTransaction();
                
                    $record->update($data); // Ensure $data is properly sanitized and validated
                
                    DB::commit();
                
                    $this->dialog()->success(
                        title: 'Order Updated',
                        description: 'The item has been successfully updated in your cart.' // Change message to match the action (updated vs. deleted)
                    );
                
                    return $record;
                } catch (\Exception $e) {
                    DB::rollBack();
                
                    $this->dialog()->error(
                        title: 'Error',
                        description: 'Failed to update the item. Please try again.' // Adjusted message to reflect the actual action
                    );
                
                   
                    \Log::error('Order update failed', ['error' => $e->getMessage()]);
                
                  
                    throw $e;
                }
                
                   
            })
            ->icon('heroicon-m-pencil-square')
             ->iconButton()
            ->form(FilamentForm::orderForm())
            ->label('Update Address')
            ->modalHeading('Update Order Details')


              ;
    }
}
