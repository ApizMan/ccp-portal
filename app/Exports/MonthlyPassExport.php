<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyPassExport implements FromCollection, WithHeadings
{
    protected $data_monthly_pass;
    protected $data_users;
    protected $datas;

    public function __construct()
    {
        // Fetch data from external URLs
        $this->fetchData();
    }

    /**
     * Fetch and prepare data based on relationships.
     */
    private function fetchData()
    {
        // Fetch parking data
        $url = env('BASE_URL') . '/monthlyPass/public';
        $data = file_get_contents($url);
        $this->data_monthly_pass = json_decode($data, true);

        // Fetch users data
        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true);

        // Initialize $datas array
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_monthly_pass as $monthlyPass) {
            $userId = $monthlyPass['userId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            $user = $user ? array_values($user)[0] : null;

            // dd($user);

            // Format the createdAt timestamp
            $createdAt = (new \DateTime($monthlyPass['createdAt']))->format('d-m-Y H:i');

            // Add data to $datas array
            $this->datas[] = [
                'id' => $monthlyPass['id'],
                'user' => $user, // Store the user array
                'plateNumber' => $monthlyPass['plateNumber'],
                'pbt' => $monthlyPass['pbt'],
                'location' => $monthlyPass['location'],
                'amount' => $monthlyPass['amount'] ?? 'N/A', // Use amount from transaction if available
                'duration' => $monthlyPass['duration'] ?? 'N/A', // Use status from transaction if available
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new \DateTime($monthlyPass['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
            ];
        }
    }

    /**
     * Prepare the collection of data for Excel export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Prepare export data based on the $datas array
        $mappedData = array_map(function ($item) {
            return [
                'Name' => ($item['user']['firstName'] ?? '') . ' ' . ($item['user']['secondName'] ?? 'N/A'), // Use user firstName and secondName if available
                'Plate Number' => $item['plateNumber'],
                'PBT' => $item['pbt'],
                'Location' => $item['location'],
                'Amount (RM)' => $item['amount'], // Use amount from transaction
                'Duration' => $item['duration'], // Use status from transaction
                'Created At' => $item['createdAt'],
            ];
        }, $this->datas);

        return new Collection($mappedData);
    }

    /**
     * Define the headings for the exported Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Plate Number',
            'PBT',
            'Location',
            'Amount (RM)',
            'Duration',
            'Created At',
        ];
    }
}
