<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class BuyerDashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Product::hasQuantity()->paginate(20);

        return view('livewire.buyer-dashboard', ['products' => $products]);
    }
}
