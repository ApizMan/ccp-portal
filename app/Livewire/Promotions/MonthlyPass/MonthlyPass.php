<?php

namespace App\Livewire\Promotions\MonthlyPass;

use Livewire\Component;

class MonthlyPass extends Component
{
    protected $data_users;
    public $data_promotion = []; // Initialize as an array
    public $datas = []; // Initialize as an array

    public function mount()
    {
        $this->fetchData();
    }

    private function fetchData()
    {
        try {
            $url = env('BASE_URL') . '/promotion/public';
            $data = file_get_contents($url);

            if ($data) {
                $this->data_promotion = json_decode($data, true);

                if (is_null($this->data_promotion)) {
                    $this->data_promotion = [];
                }
            } else {
                $this->data_promotion = [];
            }

            $this->datas = [];

            foreach ($this->data_promotion as $monthlyPass) {
                // First, check if the required fields exist
                if (isset($monthlyPass['id'], $monthlyPass['title'], $monthlyPass['date'], $monthlyPass['expiredDate'], $monthlyPass['createdAt'], $monthlyPass['updatedAt'], $monthlyPass['image'])) {
                    // Then, check if the type is 'MonthlyPass'
                    if ($monthlyPass['type'] == 'MonthlyPass') {
                        $date = (new \DateTime($monthlyPass['date']))->format('d-m-Y H:i');
                        $expired = (new \DateTime($monthlyPass['expiredDate']))->format('d-m-Y H:i');
                        $createdAt = (new \DateTime($monthlyPass['createdAt']))->format('d-m-Y H:i');

                        $this->datas[] = [
                            'id' => $monthlyPass['id'],
                            'title' => $monthlyPass['title'],
                            'description' => $monthlyPass['description'] ?? 'N/A',
                            'rate' => number_format($monthlyPass['rate'], 2),
                            'date' => $date,
                            'expired' => $expired ?? 'N/A',
                            'image' => $monthlyPass['image'],
                            'createdAt' => $createdAt,
                            'updatedAt' => (new \DateTime($monthlyPass['updatedAt']))->format('d-m-Y H:i'),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            $this->datas = [];
        }
    }


    public function render()
    {
        return view('livewire.promotions.monthly-pass.monthly-pass');
    }
}
