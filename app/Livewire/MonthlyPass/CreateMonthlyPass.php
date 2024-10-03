<?php

namespace App\Livewire\MonthlyPass;

use DateTime;
use Livewire\Component;

include_once app_path('constants.php');

class CreateMonthlyPass extends Component
{
    public $logo;
    public $logo_white;
    public $favicon;
    public $data_users;
    public $data_pbt;

    public function mount()
    {
        $this->fetchData();
        $this->logo = CCP_LOGO;
        $this->logo_white = CCP_LOGO_WHITE;
        $this->favicon = FAVICON;
    }

    public function fetchData()
    {

        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true); // true for associative array

        $url = env('BASE_URL') . '/pbt/public';
        $data = file_get_contents($url);
        $this->data_pbt = json_decode($data, true); // true for associative array
    }

    public function render()
    {
        return view('livewire.monthly-pass.create-monthly-pass');
    }
}
