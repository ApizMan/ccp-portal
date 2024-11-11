<?php

namespace App\Livewire\MonthlyPass;

use DateTime;
use Livewire\Component;

include_once app_path('constants.php');

class EditMonthlyPass extends Component
{
    public $monthlyPassId;
    public $logo;
    public $logo_white;
    public $favicon;

    public $data_users;
    public $data_pbt;
    public $data;

    public $monthlyPassData;
    public $data_promotions;

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

        $url = env('BASE_URL') . '/promotion/public';
        $data = file_get_contents($url);
        $this->data_promotions = json_decode($data, true); // true for associative array

        // dd($this->monthlyPassData);

        // Build the $datas array based on relationships
        $userId = $this->monthlyPassData['userId'];

        $promotionId = $this->monthlyPassData['promotionId'];

        // Find user by userId
        $user = array_filter($this->data_users, function ($user) use ($userId) {
            return $user['id'] === $userId;
        });

        // Find user by promotionId
        $event = array_filter($this->data_promotions, function ($promotion) use ($promotionId) {
            return $promotion['id'] === $promotionId;
        });

        // Format the createdAt timestamp
        $createdAt = (new DateTime($this->monthlyPassData['createdAt']))->format('d-m-Y H:i');

        // Use array_values to reindex arrays from array_filter
        $this->data = [
            'id' => $this->monthlyPassData['id'],
            'user' => $user ? array_values($user)[0] : null, // Store the user array
            'event' => $event ? array_values($event)[0] : null, // Store the user array
            'noReceipt' => $this->monthlyPassData['noReceipt'],
            'plateNumber' => $this->monthlyPassData['plateNumber'],
            'pbt' => $this->monthlyPassData['pbt'],
            'location' => $this->monthlyPassData['location'],
            'amount' => $this->monthlyPassData['amount'],
            'duration' => $this->monthlyPassData['duration'],
            'createdAt' => $createdAt, // Use formatted date
            'updatedAt' => (new DateTime($this->monthlyPassData['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
        ];

        // dd($this->data_pbt);
    }

    public function render()
    {
        return view('livewire.monthly-pass.edit-monthly-pass');
    }
}
