<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParkingExport implements FromCollection, WithHeadings
{
    protected $data_parking;
    protected $data_users;
    protected $data_transactions;
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
        $url = env('BASE_URL') . '/parking/public';
        $data = file_get_contents($url);
        $this->data_parking = json_decode($data, true);

        // Fetch users data
        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true);

        // Fetch transactions data
        $url = env('BASE_URL') . '/transaction/allTransactionWallet';
        $data = file_get_contents($url);
        $this->data_transactions = json_decode($data, true);

        // Initialize $datas array
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_parking as $parking) {
            $userId = $parking['userId'];
            $walletTransactionId = $parking['walletTransactionId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            $user = $user ? array_values($user)[0] : null;

            // dd($user);

            // Find transaction by walletTransactionId
            $transaction = array_filter($this->data_transactions, function ($transaction) use ($walletTransactionId) {
                return $transaction['id'] === $walletTransactionId;
            });

            // Format the createdAt timestamp
            $createdAt = (new \DateTime($parking['createdAt']))->format('d-m-Y H:i');

            // Use array_values to reindex arrays from array_filter
            $transaction = $transaction ? array_values($transaction)[0] : null;

            // Add data to $datas array
            $this->datas[] = [
                'id' => $parking['id'],
                'user' => $user, // Store the user array
                'transaction' => $transaction, // Store the transaction array
                'plateNumber' => $parking['plateNumber'],
                'pbt' => $parking['pbt'],
                'location' => $parking['location'],
                'amount' => $transaction['amount'] ?? 'N/A', // Use amount from transaction if available
                'status' => $transaction['status'] ?? 'N/A', // Use status from transaction if available
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new \DateTime($parking['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
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
                'Status' => $item['status'], // Use status from transaction
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
            'Status',
            'Created At',
        ];
    }
}
