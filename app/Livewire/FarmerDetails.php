<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farmer;

class FarmerDetails extends Component
{

    use WithPagination;
    public $farmerId;

    public function mount($farmerId)
    {
        $this->farmerId = $farmerId;
    }



    public function render()
    {
        $farmer = Farmer::with('products.category')->findOrFail($this->farmerId);

        return view('livewire.farmer-details', [
            'farmer' => $farmer,
            'products' => $farmer->products()->withRelations()->paginate(12),
        ]);
    }
}
