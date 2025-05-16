<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PublicProductDetails extends Component
{
    public $product;

    public function mount($code, $slug)
    {
        $this->product = Product::withRelations()->where('code', $code)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public-product-details', [
            'product' => $this->product,
            'isLoggedIn' => Auth::check(),
        ]);
    }
}
