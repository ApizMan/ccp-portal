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

    public $reserveBayData;

    public function mount()
    {
        $this->monthlyPassPart();
        $this->parkingPart();
        $this->reserveBayPart();
        $this->logo = CCP_LOGO;
        $this->logo_white = CCP_LOGO_WHITE;
        $this->favicon = FAVICON;
    }


    public function parkingPart()
    {
        $url = env('BASE_URL') . '/parking/public';
        $data = file_get_contents($url);
        $decodedData = json_decode($data, true); // true for associative array

        // Initialize arrays for labels and data
        $this->parking = [];
        $labels = [];
        $counts = [];

        // Get the current date and calculate the date twelve months ago
        $currentDate = \Carbon\Carbon::now();
        $twelveMonthsAgo = $currentDate->copy()->subMonths(12);

        // Loop through the decoded data
        foreach ($decodedData as $entry) {
            // Parse the 'createdAt' field
            $createdAt = \Carbon\Carbon::parse($entry['createdAt']); // Use Carbon to parse the date

            // Check if the entry is within the last twelve months
            if ($createdAt->between($twelveMonthsAgo, $currentDate)) {
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

        // Check if there are no counts to display
        if (empty($counts)) {
            $this->parking['labels'] = ['No Data Available']; // Meaningful label
            $this->parking['counts'] = [1]; // Single data point to show
        } else {
            $this->parking['labels'] = $labels; // Valid labels from the data
            $this->parking['counts'] = $counts; // Valid counts from the data
        }
    }


    public function monthlyPassPart()
    {
        $url = env('BASE_URL') . '/monthlyPass/public';
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
        if (!empty($decodedData)) {
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

        } else {
            // Handle the case where no data is available
            $this->monthlyPassData['labels'] = ['No Data Available']; // Meaningful label
            $this->monthlyPassData['amounts'] = [1]; // Single data point to show
            $this->monthlyPassData['colors'] = ['#6c757d']; // Default color
        }
    }

    public function reserveBayPart()
    {
        $url = env('BASE_URL') . '/reserveBay/public';
        $data = file_get_contents($url);
        $decodedData = json_decode($data, true); // true for associative array

        // Initialize arrays for labels and counts
        $labels = [];
        $counts = [];

        // Get the current date and calculate the date six months ago
        $currentDate = \Carbon\Carbon::now();
        $sixMonthsAgo = $currentDate->copy()->subMonths(12); // Get the last 6 months

        // Loop through the decoded data
        foreach ($decodedData as $entry) {
            // Parse the 'createdAt' field
            $createdAt = \Carbon\Carbon::parse($entry['createdAt']); // Use Carbon to parse the date

            // Check if the entry is within the last six months
            if ($createdAt->between($sixMonthsAgo, $currentDate)) {
                $date = $createdAt->format('M Y'); // Format as month and year

                // Check if the date already exists in the labels
                if (!in_array($date, $labels)) {
                    $labels[] = $date;
                    $counts[] = 1; // Initialize count for this date
                } else {
                    // If the date exists, update the count
                    $index = array_search($date, $labels);
                    $counts[$index]++;
                }
            }
        }

        $this->reserveBayData['reserveBayLabels'] = $labels;
        $this->reserveBayData['reserveBayCounts'] = $counts;
    }

    public function render()
    {
        return view('livewire.home.dashboard');
    }
}
