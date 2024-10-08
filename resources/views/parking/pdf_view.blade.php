<!DOCTYPE html>
<html>

<head>
    <title>Parking Data</title>
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
    <h1>Parking Records</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Plate Number</th>
                <th>PBT</th>
                <th>Location</th>
                <th>Amount (RM)</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['user']['firstName'] }} {{ $item['user']['secondName'] }}</td>
                    <td>{{ $item['plateNumber'] }}</td>
                    <td>{{ $item['pbt'] }}</td>
                    <td>{{ $item['location'] }}</td>
                    <td>{{ number_format($item['amount'], 2) }}</td>
                    <td>{{ strtoupper($item['status']) }}</td>
                    <td>{{ $item['createdAt'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
