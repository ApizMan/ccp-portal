<?php

namespace App\Livewire\Compound;

use DateTime;
use Livewire\Component;

class Compound extends Component
{
    public $data_compound;

    public $data_users;

    public $datas;
    public function mount()
    {
        $this->fetchData();
    }

    public function fetchData()
    {
        $url = env('BASE_URL') . '/compound/public';
        $data = file_get_contents($url);
        $this->data_compound = json_decode($data, true); // true for associative array

        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true); // true for associative array

        // Initialize $datas
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_compound as $compound) {
            $userId = $compound['userId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            // Format the createdAt timestamp
            $paymentDate = (new DateTime($compound['PaymentDate']))->format('d-m-Y H:i');
            $createdAt = (new DateTime($compound['createdAt']))->format('d-m-Y H:i');

            // Use array_values to reindex arrays from array_filter
            $this->datas[] = [
                'id' => $compound['id'],
                'user' => $user ? array_values($user)[0] : null, // Store the user array
                'ownerIdNo' => $compound['OwnerIdNo'],
                'ownerCategoryId' => $compound['OwnerCategoryId'],
                'vehicleRegistrationNumber' => $compound['VehicleRegistrationNumber'],
                'noticeNo' => $compound['NoticeNo'],
                'receiptNo' => $compound['ReceiptNo'],
                'paymentTransactionType' => $compound['PaymentTransactionType'],
                'paymentDate' => $paymentDate,
                'paidAmount' => $compound['PaidAmount'],
                'channelType' => $compound['ChannelType'],
                'paymentStatus' => $compound['PaymentStatus'],
                'paymentMode' => $compound['PaymentMode'],
                'paymentLocation' => $compound['PaymentLocation'],
                'notes' => $compound['Notes'],
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new DateTime($compound['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];
        }
    }
    public function render()
    {
        return view('livewire.compound.compound');
    }
}
