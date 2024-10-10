<?php

namespace App\Livewire\MonthlyPass;

use DateTime;
use Livewire\Component;

include_once app_path('constants.php');

class MonthlyPass extends Component
{
    public $logo;
    public $logo_white;
    public $favicon;
    public $data_monthly_pass;
    public $data_users;
    public $datas;

    public $promotion;

    public function mount()
    {
        $this->fetchData();
        $this->logo = CCP_LOGO;
        $this->logo_white = CCP_LOGO_WHITE;
        $this->favicon = FAVICON;
    }

    public function fetchData()
    {
        $url = env('BASE_URL') . '/monthlyPass/public';
        $data = file_get_contents($url);
        $this->data_monthly_pass = json_decode($data, true); // true for associative array

        // dd($this->data_monthly_pass);

        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true); // true for associative array

        $url = env('BASE_URL') . '/promotion/public';
        $data = file_get_contents($url);
        $this->promotion = json_decode($data, true); // true for associative array

        // Initialize $datas
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_monthly_pass as $monthlyPass) {
            $userId = $monthlyPass['userId'];
            $promotionId = $monthlyPass['promotionId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            // Find user by userId
            $event = array_filter($this->promotion, function ($promotion) use ($promotionId) {
                return $promotion['id'] === $promotionId;
            });

            // Format the createdAt timestamp
            $createdAt = (new DateTime($monthlyPass['createdAt']))->format('d-m-Y H:i');

            // Use array_values to reindex arrays from array_filter
            $this->datas[] = [
                'id' => $monthlyPass['id'],
                'user' => $user ? array_values($user)[0] : null, // Store the user array
                'event' => $event ? array_values($event)[0] : null,
                'plateNumber' => $monthlyPass['plateNumber'],
                'pbt' => $monthlyPass['pbt'],
                'location' => $monthlyPass['location'],
                'amount' => $monthlyPass['amount'],
                'duration' => $monthlyPass['duration'],
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new DateTime($monthlyPass['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];
        }
    }

    public function render()
    {
        return view('livewire.monthly-pass.monthly-pass');
    }
}
