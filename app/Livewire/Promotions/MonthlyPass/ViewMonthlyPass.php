<?php

namespace App\Livewire\Promotions\MonthlyPass;

use DateTime;
use Livewire\Component;

class ViewMonthlyPass extends Component
{

    public $promotionData;
    public $data;

    public function mount()
    {
        // Format the createdAt timestamp
        $date = (new DateTime($this->promotionData['date']))->format('d-m-Y H:i');
        $expired = (new DateTime($this->promotionData['expiredDate']))->format('Y-m-d\TH:i'); // Format for datetime-local
        $createdAt = (new DateTime($this->promotionData['createdAt']))->format('d-m-Y H:i');

        // Use array_values to reindex arrays from array_filter
        $this->data = [
            'id' => $this->promotionData['id'],
            'title' => $this->promotionData['title'],
            'description' => $this->promotionData['description'],
            'rate' => number_format($this->promotionData['rate'], 2),
            'frequency' => $this->promotionData['frequency'],
            'date' => $date,
            'expired' => $expired,
            'image' => $this->promotionData['image'],
            'createdAt' => $createdAt, // Use formatted date
            'updatedAt' => (new DateTime($this->promotionData['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
        ];
    }

    public function render()
    {
        return view('livewire.promotions.monthly-pass.view-monthly-pass');
    }
}
