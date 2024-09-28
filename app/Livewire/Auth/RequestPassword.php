<?php

namespace App\Livewire\Auth;

use Livewire\Component;

include_once app_path('constants.php');

class RequestPassword extends Component
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
        return view('livewire.auth.request-password');
    }
}
