<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Filament\Actions\EditAction;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

class OrderHistory extends Component implements HasForms, HasActions
{
    use WithPagination;
    use InteractsWithActions;
    use InteractsWithForms;
    use WireUiActions;


    public $search = '';
    public $status = '';
    public $statusCounts = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => '']
    ];

    public function mount()
    {
        $this->calculateStatusCounts();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function chatFarmer($orderId)
{
    $order = Order::with('farmer.user')->findOrFail($orderId);
    $otherUser = $order->farmer->user;

    // Create or fetch the conversation with the farmer
    $conversation = auth()->user()->createConversationWith($otherUser, 'Order Discussion');

    // Redirect to the chat route provided by WireChat
    return redirect()->route('chat', ['conversation_id' => $conversation->id]);
    
}

    

    public function calculateStatusCounts()
    {
        $this->statusCounts = Order::query()
            ->where('buyer_id', Auth::user()->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function receiveOrderAction(): EditAction
    {
        return EditAction::make('receiveOrder')
            ->iconButton()
            ->color('gray')
            ->record(function (array $arguments) {
                return Order::findOrFail($arguments['record']);
            })
            ->requiresConfirmation()
            ->fillForm(function (array $arguments) {
                $record = Order::find($arguments['record']);
                return [
                    'is_received' => $record->region,
                ];
            })
          
            ->icon('heroicon-m-pencil-square')
            ->iconButton()
            ->form([
                Toggle::make('is_received')->label('Receive Oder')
            ])
            ->label('Receive Order')
            ->modalHeading('Mark ')


        ;
    }

    public function render()
    {
        $orders = Order::query()
            ->where('buyer_id', Auth::user()->id)
            ->notProcessing()
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', "%{$this->search}%")
                    ->orWhereHas('items.product', function ($query) {
                        $query->where('product_name', 'like', "%{$this->search}%");
                    });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->with(['items.product'])
            ->orderBy('order_date', 'desc')
            ->paginate(10);

        return view('livewire.order-history', [
            'orders' => $orders,
            'statuses' => array_diff(Order::STATUS_OPTIONS, [Order::PROCESSING]),
        ]);
    }
}
