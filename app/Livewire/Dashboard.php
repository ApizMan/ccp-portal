<?php

namespace App\Livewire;

use Livewire\Component;

include_once app_path('constants.php');

class Dashboard extends Component
{
    public $logo;
    public $logo_white;
    public $favicon;
    public $parking;

    public function mount()
    {
        $this->parkingPart();
        $this->logo = CCP_LOGO;
        $this->logo_white = CCP_LOGO_WHITE;
        $this->favicon = FAVICON;
    }

    public function parkingPart()
    {
        $url = BASE_URL . '/parking/public';
        $data = file_get_contents($url);
        $decodedData = json_decode($data, true); // true for associative array

        // Initialize arrays for labels and data
        $this->parking = [];
        $labels = [];
        $counts = [];

        // Get the current date and calculate the date six months ago
        $currentDate = \Carbon\Carbon::now();
        $sixMonthsAgo = $currentDate->copy()->subMonths(12);

        // Loop through the decoded data
        foreach ($decodedData as $entry) {
            // Parse the 'createdAt' field
            $createdAt = \Carbon\Carbon::parse($entry['createdAt']); // Use Carbon to parse the date

            // Check if the entry is within the last six months
            if ($createdAt->between($sixMonthsAgo, $currentDate)) {
                $date = $createdAt->format('M Y'); // Format as month and year
                $count = 1; // Set count to 1 for each entry

                // Check if the date already exists in the labels
                if (!in_array($date, $labels)) {
                    $labels[] = $date;
                    $counts[] = $count; // Initialize count for this date
                } else {
                    // If the date exists, update the count
                    $index = array_search($date, $labels);
                    $counts[$index] += $count;
                }
            }
        }

        $this->parking['labels'] = $labels;
        $this->parking['counts'] = $counts;
    }

    public function render()
    {
        return view('livewire.home.dashboard');
    }
}
