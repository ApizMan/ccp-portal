<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReserveBayExport implements FromCollection, WithHeadings
{
    protected $data_reserve_bay;
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
        $url = env('BASE_URL') . '/reservebay/public';
        $data = file_get_contents($url);
        $this->data_reserve_bay = json_decode($data, true);

        // Fetch users data
        $url = env('BASE_URL') . '/auth/users';
        $data = file_get_contents($url);
        $this->data_users = json_decode($data, true);

        // Initialize $datas array
        $this->datas = [];

        // Build the $datas array based on relationships
        foreach ($this->data_reserve_bay as $reserveBay) {
            $userId = $reserveBay['userId'];

            // Find user by userId
            $user = array_filter($this->data_users, function ($user) use ($userId) {
                return $user['id'] === $userId;
            });

            $user = $user ? array_values($user)[0] : null;

            // dd($user);

            // Format the createdAt timestamp
            $createdAt = (new \DateTime($reserveBay['createdAt']))->format('d-m-Y H:i');

            // Add data to $datas array
            $this->datas[] = [
                'id' => $reserveBay['id'],
                'status' => $reserveBay['status'],
                'user' => $user ? array_values($user)[0] : null, // Store the user array
                'companyName' => $reserveBay['companyName'],
                'companyRegistration' => $reserveBay['companyRegistration'],
                'businessType' => $reserveBay['businessType'],
                'address1' => $reserveBay['address1'],
                'address2' => $reserveBay['address2'],
                'address3' => $reserveBay['address3'],
                'postcode' => $reserveBay['postcode'],
                'city' => $reserveBay['city'],
                'state' => $reserveBay['state'],
                'location' => $reserveBay['location'],
                'picFirstName' => $reserveBay['picFirstName'],
                'picLastName' => $reserveBay['picLastName'],
                'phoneNumber' => $reserveBay['phoneNumber'],
                'email' => $reserveBay['email'],
                'idNumber' => $reserveBay['idNumber'],
                'totalLotRequired' => $reserveBay['totalLotRequired'],
                'reason' => $reserveBay['reason'],
                'lotNumber' => $reserveBay['lotNumber'],
                'designatedBayPicture' => $reserveBay['designatedBayPicture'],
                'registerNumberPicture' => $reserveBay['registerNumberPicture'],
                'idCardPicture' => $reserveBay['idCardPicture'],
                'createdAt' => $createdAt, // Use formatted date
                'updatedAt' => (new \DateTime($reserveBay['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
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
                'Company Name' => $item['companyName'] ?? '',
                'Company Registration' => $item['companyRegistration'] ?? '',
                'Business Type' => $item['businessType'] ?? '',
                'Address' => $item['address1'] . ', ' . $item['address2'] . ', ' . $item['address3'] . ', ' . $item['postcode'] . ', ' . $item['city'] . ', ' . $item['state'] . ', ' . $item['location'] ?? '',
                'PIC Name' => $item['picFirstName'] . ' ' . $item['picLastName'] ?? '',
                'Phone Number' => $item['phoneNumber'] ?? '',
                'Email' => $item['email'] ?? '',
                'ID Number' => $item['idNumber'] ?? '',
                'Total Lot Required' => $item['totalLotRequired'] ?? '',
                'Reason' => $item['reason'] ?? '',
                'Lot Number' => $item['lotNumber'] ?? '',
                'Designated Bay Picture' => $item['designatedBayPicture'] ?? '',
                'Register Number Picture' => $item['registerNumberPicture'] ?? '',
                'ID Card Picture' => $item['idCardPicture'] ?? '',
                'Created At' => $item['createdAt'] ?? '',
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
            'Company Name',
            'Company Registration',
            'Business Type',
            'Address',
            'PIC Name',
            'Phone Number',
            'Email',
            'ID Number',
            'Total Lot Required',
            'Reason',
            'Lot Number',
            'Designated Bay Picture',
            'Register Number Picture',
            'Created At'
        ];
    }
}
