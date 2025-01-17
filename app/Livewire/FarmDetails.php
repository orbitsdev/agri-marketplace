<?php

namespace App\Livewire;

use App\Models\Farmer;
use Livewire\Component;

class FarmDetails extends Component
{

    public $record;


    public function mount($record){
       $this->record = Farmer::findOrFail($record); 
    }
    public function render()
    {
        return view('livewire.farm-details');
    }
}
