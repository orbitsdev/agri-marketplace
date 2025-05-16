<?php

namespace App\Livewire;

use App\Models\Farmer;
use Livewire\Component;

class PublicFarmerDetails extends Component
{
    public $farmer;
    public $products;

    public function mount($farmerId)
    {
        $this->farmer = Farmer::with('user')->findOrFail($farmerId);
        $this->products = $this->farmer->products()
            ->published()
            ->available()
            ->get();
    }

    public function render()
    {
        return view('livewire.public-farmer-details', [
            'farmer' => $this->farmer,
            'products' => $this->products,
        ]);
    }
}
