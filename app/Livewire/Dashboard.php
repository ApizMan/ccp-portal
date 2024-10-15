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

    public $compoundData;

    public function mount()
    {
        $this->monthlyPassPart();
        $this->parkingPart();
        $this->reserveBayPart();
        $this->compoundPart();
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

        // Define colors for each location
        $locationColors = [
            'Kelantan' => '#fc7f03',    // Orange
            'Pahang' => '#0384fc',       // Blue
            'Terengganu' => '#fcd703',   // Yellow
        ];

        // Loop through the data and count occurrences by location
        if (!empty($decodedData)) {
            foreach ($decodedData as $entry) {
                $location = $entry['location'];

                // Initialize count for this location
                if (!isset($counts[$location])) {
                    $counts[$location] = 0;
                }

                // Increment the count for this location
                $counts[$location]++;
            }

            // Prepare the data for the chart
            foreach ($counts as $location => $count) {
                $labels[] = $location; // Use the location as the label
                $this->monthlyPassData['amounts'][] = $count; // Count for this location
                $this->monthlyPassData['colors'][] = $locationColors[$location] ?? '#6c757d'; // Default color
            }

            // Assign labels
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

    public function compoundPart()
    {
        $url = env('BASE_URL') . '/compound/public';
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
            $createdAt = \Carbon\Carbon::parse($entry['PaymentDate']); // Use Carbon to parse the date

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

        $this->compoundData['compoundLabels'] = $labels;
        $this->compoundData['compoundCounts'] = $counts;

        // dd($this->compoundData);
    }

    public function render()
    {
        return view('livewire.home.dashboard');
    }
}
