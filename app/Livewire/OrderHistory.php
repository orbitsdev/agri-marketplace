<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OrderHistory extends Component
{
    use WithPagination;

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

    public function calculateStatusCounts()
    {
        $this->statusCounts = Order::query()
            ->where('buyer_id', Auth::user()->id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
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
