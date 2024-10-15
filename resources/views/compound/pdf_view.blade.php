<!DOCTYPE html>
<html>

<head>
    <title>Compound Data</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Compound Records</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Plate Number</th>
                <th>Notice No.</th>
                <th>Receipt No.</th>
                <th>Amount (RM)</th>
                <th>Payment Method</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['user']['firstName'] }} {{ $item['user']['secondName'] }}</td>
                    <td>{{ $item['vehicleRegistrationNumber'] }}</td>
                    <td>{{ $item['noticeNo'] }}</td>
                    <td>{{ $item['receiptNo'] }}</td>
                    <td>{{ number_format($item['paidAmount'], 2) }}</td>
                    <td>{{ $item['paymentLocation'] }}</td>
                    <td>{{ $item['paymentDate'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
