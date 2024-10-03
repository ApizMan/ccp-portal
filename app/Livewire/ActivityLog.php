<?php

namespace App\Livewire;

use App\Models\ActivityLog as ModelsActivityLog;
use Livewire\Component;

include_once app_path('constants.php');

class ActivityLog extends Component
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
        $this->datas = ModelsActivityLog::all();
    }

    public function render()
    {
        return view('livewire.activity-log');
    }
}
