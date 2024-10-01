<?php

namespace App\Livewire\Parking;

use DateTime;
use Livewire\Component;

include_once app_path('constants.php');

class Parking extends Component
{

    public $logo;
    public $logo_white;
    public $favicon;

    public $data_parking;
    public $data_users;
    public $data_transactions;
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
        $url = env('BASE_URL') . '/parking/public';
        $data = file_get_contents($url);
        $this->data_parking = json_decode($data, true); // true for associative array

        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true); // true for associative array

        $url = env('BASE_URL') . '/transaction/allTransactionWallet';
        $data = file_get_contents($url);
        $this->data_transactions = json_decode($data, true); // true for associative array

        // Initialize $datas
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_parking as $parking) {
            $userId = $parking['userId'];
            $walletTransactionId = $parking['walletTransactionId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            // Find transaction by walletTransactionId
            $transaction = array_filter($this->data_transactions, function ($transaction) use ($walletTransactionId) {
                return $transaction['id'] === $walletTransactionId;
            });

            // Format the createdAt timestamp
            $createdAt = (new DateTime($parking['createdAt']))->format('d-m-Y H:i');

            // Use array_values to reindex arrays from array_filter
            $this->datas[] = [
                'id' => $parking['id'],
                'user' => $user ? array_values($user)[0] : null, // Store the user array
                'transaction' => $transaction ? array_values($transaction)[0] : null, // Store the transaction array
                'plateNumber' => $parking['plateNumber'],
                'pbt' => $parking['pbt'],
                'location' => $parking['location'],
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new DateTime($parking['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];
        }
    }



    public function render()
    {
        return view('livewire.parking.parking');
    }
}
