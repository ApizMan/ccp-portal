<?php

namespace App\Livewire;

use Livewire\Component;

include_once app_path('constants.php');

class Parking extends Component
{

    public $logo;
    public $logo_white;
    public $favicon;

    public $datas;

    public function mount()
    {
        $this->fetchData();
        $this->logo = CCP_LOGO;
        $this->logo_white = CCP_LOGO_WHITE;
        $this->favicon = FAVICON;
    }

    public function fetchData()
    {
        $url = BASE_URL . '/parking/public';
        $data = file_get_contents($url);
        $this->datas = json_decode($data, true); // true for associative array

        dd($this->datas);
    }

    public function render()
    {
        return view('livewire.parking');
    }
}
