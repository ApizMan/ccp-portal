<?php

namespace App\Http\Controllers;

use App\Exports\CompoundExport;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CompoundController extends Controller
{

    protected $data_compound;
    protected $data_users;
    protected $datas;
    public function index()
    {
        return view('compound.compound');
    }

    public function view($id)
    {
        $reserveBayId = $id;
        $url = env('BASE_URL') . '/compound/single/' . $id;

        // Using file_get_contents
        $data = file_get_contents($url);

        // Decode the JSON data if necessary
        $decodedData = json_decode($data, true); // true for associative array

        $urlUser = env('BASE_URL') . '/auth/users';
        $dataUser = file_get_contents($urlUser);
        $data_users = json_decode($dataUser, true); // true for associative array



        $userId = $decodedData['userId'];

        // Find user by userId
        $user = array_filter($data_users, function ($user) use ($userId) {
            return $user['id'] === $userId;
        });

        // Format the createdAt timestamp
        $paymentDate = (new DateTime($decodedData['PaymentDate']))->format('d-m-Y H:i');
        $createdAt = (new DateTime($decodedData['createdAt']))->format('d-m-Y H:i');

        // Use array_values to reindex arrays from array_filter
        $data = [
            'id' => $decodedData['id'],
            'user' => $user ? array_values($user)[0] : null, // Store the user array
            'ownerIdNo' => $decodedData['OwnerIdNo'],
            'ownerCategoryId' => $decodedData['OwnerCategoryId'],
            'vehicleRegistrationNumber' => $decodedData['VehicleRegistrationNumber'],
            'noticeNo' => $decodedData['NoticeNo'],
            'receiptNo' => $decodedData['ReceiptNo'],
            'paymentTransactionType' => $decodedData['PaymentTransactionType'],
            'paymentDate' => $paymentDate,
            'paidAmount' => $decodedData['PaidAmount'],
            'channelType' => $decodedData['ChannelType'],
            'paymentStatus' => $decodedData['PaymentStatus'],
            'paymentMode' => $decodedData['PaymentMode'],
            'paymentLocation' => $decodedData['PaymentLocation'],
            'notes' => $decodedData['Notes'],
            'createdAt' => $createdAt, // Use formatted date
            'updatedAt' => (new DateTime($decodedData['updatedAt']))->format('d-m-Y H:i'), // Format updatedAt as well
        ];

        // dd($datas);

        return view(
            'compound.view-compound',
            compact(
                [
                    'data',
                ],
            ),
        );
    }

    public function exportExcel()
    {
        return Excel::download(new CompoundExport, 'parking_data.xlsx');
    }

    public function exportPDF()
    {
        // Increase memory limit
        ini_set('memory_limit', '512M'); // or '512M'

        // Fetch the data needed for the PDF.
        $this->fetchData();

        // Check if $this->datas is populated correctly.
        $data = $this->datas;

        // dd($data);

        // If there's no data, handle accordingly.
        if (empty($data)) {
            return back()->with('error', 'No data available for export.');
        }

        // Generate PDF using the data and view.
        $pdf = Pdf::loadView('compound.pdf_view', compact('data'));

        // dd($pdf);
        // Return the generated PDF for download.
        return $pdf->download('compound_data.pdf');
    }

    private function fetchData()
    {
        // Fetch compound data
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

            // dd($user);
            // Format the createdAt timestamp
            $paymentDate = (new DateTime($compound['PaymentDate']))->format('d-m-Y H:i');
            $createdAt = (new DateTime($compound['createdAt']))->format('d-m-Y H:i');

            // Use array_values to reindex arrays from array_filter
            $this->datas[] = [
                'id' => $compound['id'],
                'user' => $user, // Store the user array
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
}
