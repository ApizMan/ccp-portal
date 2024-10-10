<?php

namespace App\Livewire\Promotions\MonthlyPass;

use DateTime;
use Livewire\Component;

class EditMonthlyPass extends Component
{

    public $promotionData;
    public $data;

    public function mount()
    {
        $this->fetchData();
    }

    public function fetchData()
    {
        // Format the createdAt timestamp
        $date = (new DateTime($this->promotionData['date']))->format('Y-m-d\TH:i'); // Format for datetime-local
        $expired = (new DateTime($this->promotionData['expiredDate']))->format('Y-m-d\TH:i'); // Format for datetime-local
        $createdAt = (new DateTime($this->promotionData['createdAt']))->format('d-m-Y H:i');

        // Use array_values to reindex arrays from array_filter
        $this->data = [
            'id' => $this->promotionData['id'],
            'title' => $this->promotionData['title'],
            'description' => $this->promotionData['description'],
            'rate' => number_format($this->promotionData['rate'], 2),
            'date' => $date,
            'expired' => $expired,
            'image' => $this->promotionData['image'],
            'createdAt' => $createdAt, // Use formatted date
            'updatedAt' => (new DateTime($this->promotionData['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
        ];

        // dd($this->data);
    }

    public function render()
    {
        return view('livewire.promotions.monthly-pass.edit-monthly-pass');
    }
}
