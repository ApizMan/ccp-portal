<?php

namespace App\Exports;

use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompoundExport implements FromCollection, WithHeadings
{
    protected $data_compound;
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
        $url = env('BASE_URL') . '/compound/public';
        $data = file_get_contents($url);
        $this->data_compound = json_decode($data, true);

        // Fetch users data
        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true);

        // Initialize $datas array
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_compound as $compound) {
            $userId = $compound['userId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            $user = $user ? array_values($user)[0] : null;

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


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Prepare export data based on the $datas array
        $mappedData = array_map(function ($item) {
            return [
                'Name' => ($item['user']['firstName'] ?? '') . ' ' . ($item['user']['secondName'] ?? 'N/A'), // Use user firstName and secondName if available
                'Plate Number' => $item['vehicleRegistrationNumber'],
                'Notice No.' => $item['noticeNo'],
                'Receipt No.' => $item['receiptNo'],
                'Amount (RM)' => $item['paidAmount'], // Use amount from transaction
                'Payment Method' => $item['paymentLocation'], // Use status from transaction
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
            'Notice No.',
            'Receipt No.',
            'Amount (RM)',
            'Payment Method',
            'Created At',
        ];
    }
}
