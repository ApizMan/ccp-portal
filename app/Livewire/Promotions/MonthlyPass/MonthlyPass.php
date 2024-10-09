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
            // Fetch parking data from the given URL
            $url = env('BASE_URL') . '/promotion/public';
            $data = file_get_contents($url);

            // Check if $data is not false and decode it
            if ($data) {
                $this->data_promotion = json_decode($data, true);

                // If decoding returns null, assign an empty array
                if (is_null($this->data_promotion)) {
                    $this->data_promotion = [];
                }
            } else {
                // If file_get_contents fails, initialize $data_promotion as an empty array
                $this->data_promotion = [];
            }

            // Initialize the $datas array
            $this->datas = [];

            // Build the $datas array based on the fetched data
            foreach ($this->data_promotion as $monthlyPass) {
                // Check if required fields exist in $monthlyPass
                if (isset($monthlyPass['id'], $monthlyPass['title'], $monthlyPass['description'], $monthlyPass['date'], $monthlyPass['createdAt'], $monthlyPass['updatedAt'], $monthlyPass['image'])) {
                    // Format the createdAt and updatedAt timestamps
                    $date = (new \DateTime($monthlyPass['date']))->format('d-m-Y H:i');
                    $createdAt = (new \DateTime($monthlyPass['createdAt']))->format('d-m-Y H:i');

                    // Append each promotion to the $datas array
                    $this->datas[] = [
                        'id' => $monthlyPass['id'],
                        'title' => $monthlyPass['title'],
                        'description' => $monthlyPass['description'],
                        'date' => $date,
                        'image' => $monthlyPass['image'],
                        'createdAt' => $createdAt, // Use formatted date
                        'updatedAt' => (new \DateTime($monthlyPass['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
                    ];
                }
            }
        } catch (\Exception $e) {
            // Handle exceptions and initialize $datas as an empty array in case of an error
            $this->datas = [];
        }
    }

    public function render()
    {
        return view('livewire.promotions.monthly-pass.monthly-pass');
    }
}
