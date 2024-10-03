<?php

namespace App\Livewire\ReserveBay;

use DateTime;
use Livewire\Component;

include_once app_path('constants.php');

class ReserveBay extends Component
{
    public $logo;
    public $logo_white;
    public $favicon;
    public $data_reserve_bay;
    public $data_users;
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
        $url = env('BASE_URL') . '/reservebay/public';
        $data = file_get_contents($url);
        $this->data_reserve_bay = json_decode($data, true); // true for associative array

        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true); // true for associative array

        // Initialize $datas
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_reserve_bay as $reserve_bay) {
            $userId = $reserve_bay['userId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            // Format the createdAt timestamp
            $createdAt = (new DateTime($reserve_bay['createdAt']))->format('d-m-Y H:i');

            // Use array_values to reindex arrays from array_filter
            $this->datas[] = [
                'id' => $reserve_bay['id'],
                'user' => $user ? array_values($user)[0] : null, // Store the user array
                'companyName' => $reserve_bay['companyName'],
                'companyRegistration' => $reserve_bay['companyRegistration'],
                'businessType' => $reserve_bay['businessType'],
                'address1' => $reserve_bay['address1'],
                'address2' => $reserve_bay['address2'],
                'address3' => $reserve_bay['address3'],
                'postcode' => $reserve_bay['postcode'],
                'city' => $reserve_bay['city'],
                'state' => $reserve_bay['state'],
                'picFirstName' => $reserve_bay['picFirstName'],
                'picLastName' => $reserve_bay['picLastName'],
                'phoneNumber' => $reserve_bay['phoneNumber'],
                'email' => $reserve_bay['email'],
                'idNumber' => $reserve_bay['idNumber'],
                'totalLotRequired' => $reserve_bay['totalLotRequired'],
                'reason' => $reserve_bay['reason'],
                'lotNumber' => $reserve_bay['lotNumber'],
                'location' => $reserve_bay['location'],
                'designatedBayPicture' => $reserve_bay['designatedBayPicture'],
                'registerNumberPicture' => $reserve_bay['registerNumberPicture'],
                'idCardPicture' => $reserve_bay['idCardPicture'],
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new DateTime($reserve_bay['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];

            // dd($this->datas);
        }
    }

    public function render()
    {
        return view('livewire.reserve-bay.reserve-bay');
    }
}
