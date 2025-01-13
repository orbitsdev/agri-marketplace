<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Filament\Actions\Action;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
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
            ->icon('heroicon-o-x-mark')
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
}
