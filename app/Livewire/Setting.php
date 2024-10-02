<?php

namespace App\Livewire;

use Livewire\Component;

include_once app_path('constants.php');

class Setting extends Component
{

    public $logo;
    public $logo_white;
    public $favicon;

    public function mount()
    {
        $this->logo = CCP_LOGO;
        $this->logo_white = CCP_LOGO_WHITE;
        $this->favicon = FAVICON;
    }
    public function render()
    {
        return view('livewire.setting');
    }
}
