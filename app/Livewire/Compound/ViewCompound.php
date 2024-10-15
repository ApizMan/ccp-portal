<?php

namespace App\Livewire\Compound;

use DateTime;
use Livewire\Component;

class ViewCompound extends Component
{
    public $compoundData;

    public $data;

    public function mount()
    {
        $this->data = $this->compoundData;
        // dd($this->data);
    }


    public function render()
    {
        return view('livewire.compound.view-compound');
    }
}
