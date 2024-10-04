<?php

namespace App\Livewire\ReserveBay;

use DateTime;
use Livewire\Component;

include_once app_path('constants.php');

class ViewReserveBay extends Component
{

    public $reserveBayId;
    public $logo;
    public $logo_white;
    public $favicon;

    public $data_users;
    public $data_pbt;
    public $data;

    public $reserveBayData;

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

        // dd($this->reserveBayData);

        // Build the $datas array based on relationships
        $userId = $this->reserveBayData['userId'];

        // Find user by userId
        $user = array_filter($this->data_users, function ($user) use ($userId) {
            return $user['id'] === $userId;
        });

        // Format the createdAt timestamp
        $createdAt = (new DateTime($this->reserveBayData['createdAt']))->format('d-m-Y H:i');

        // Use array_values to reindex arrays from array_filter
        $this->data = [
            'id' => $this->reserveBayData['id'],
            'status' => $this->reserveBayData['status'],
            'user' => $user ? array_values($user)[0] : null, // Store the user array
            'companyName' => $this->reserveBayData['companyName'],
            'companyRegistration' => $this->reserveBayData['companyRegistration'],
            'businessType' => $this->reserveBayData['businessType'],
            'address1' => $this->reserveBayData['address1'],
            'address2' => $this->reserveBayData['address2'],
            'address3' => $this->reserveBayData['address3'],
            'postcode' => $this->reserveBayData['postcode'],
            'city' => $this->reserveBayData['city'],
            'state' => $this->reserveBayData['state'],
            'location' => $this->reserveBayData['location'],
            'picFirstName' => $this->reserveBayData['picFirstName'],
            'picLastName' => $this->reserveBayData['picLastName'],
            'phoneNumber' => $this->reserveBayData['phoneNumber'],
            'email' => $this->reserveBayData['email'],
            'idNumber' => $this->reserveBayData['idNumber'],
            'totalLotRequired' => $this->reserveBayData['totalLotRequired'],
            'reason' => $this->reserveBayData['reason'],
            'lotNumber' => $this->reserveBayData['lotNumber'],
            'designatedBayPicture' => $this->reserveBayData['designatedBayPicture'],
            'registerNumberPicture' => $this->reserveBayData['registerNumberPicture'],
            'idCardPicture' => $this->reserveBayData['idCardPicture'],
            'createdAt' => $createdAt, // Use formatted date
            'updatedAt' => (new DateTime($this->reserveBayData['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
        ];

        // dd($this->data_pbt);
    }

    public function render()
    {
        return view('livewire.reserve-bay.view-reserve-bay');
    }
}
