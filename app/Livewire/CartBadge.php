<?php

namespace App\Livewire;

use App\Models\Cart;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class CartBadge extends Component
{
    public $itemCount = 0;

    public function mount($itemCount = 0)
    {
        // Initialize itemCount with the passed value or fetch total cart items
        $this->itemCount = $itemCount ?: auth()->user()->getTotalCartItems();
    }

    #[On('cart.updated')]
    public function refreshItemCount()
    {



        // // Update item count if passed, otherwise fetch using the user method
         $this->itemCount = auth()->user()->getTotalCartItems();
    }

    public function render()
    {
        return view('livewire.cart-badge', ['itemCount' => $this->itemCount]);
    }
}
