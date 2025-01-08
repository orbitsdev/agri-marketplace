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
}
