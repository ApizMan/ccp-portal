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

    public $monthlyPassData;

    public function mount()
    {
        $this->monthlyPassPart();
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

    public function monthlyPassPart()
    {
        $url = BASE_URL . '/monthlyPass/public';
        $data = file_get_contents($url);
        $decodedData = json_decode($data, true); // true for associative array

        // Initialize arrays to store data for the chart
        $labels = [];
        $counts = [];

        // Define colors for each duration
        $durationColors = [
            '1 Month' => '#007bff',   // Blue
            '3 Months' => '#dc3545',  // Red
            '12 Months' => '#28a745',  // Green
        ];

        // Loop through the data and count occurrences by location and duration
        foreach ($decodedData as $entry) {
            $location = $entry['location'];
            $duration = $entry['duration'];

            // Initialize label and count for this location and duration
            if (!isset($counts[$location][$duration])) {
                $counts[$location][$duration] = 0;
            }

            // Increment the count for this location and duration
            $counts[$location][$duration]++;
        }

        // Prepare the data for the chart
        foreach ($counts as $location => $durations) {
            foreach ($durations as $duration => $count) {
                $labels[] = $location; // Use the location as the label
                $this->monthlyPassData['amounts'][] = $count; // Count for this location and duration
                $this->monthlyPassData['colors'][] = $durationColors[$duration] ?? '#6c757d'; // Default color
            }
        }

        // Assign labels and amounts
        $this->monthlyPassData['labels'] = array_unique($labels); // Unique locations
    }

    public function render()
    {
        return view('livewire.home.dashboard');
    }
}
