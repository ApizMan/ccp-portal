<!DOCTYPE html>
<html>

<head>
    <title>Monthly Pass Data</title>
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
    @php
        include_once app_path('constants.php');
        $favicon = FAVICON;
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
</head>

<body>
    <h1>Monthly Pass Records</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Plate Number</th>
                <th>PBT</th>
                <th>Location</th>
                <th>Amount (RM)</th>
                <th>Duration</th>
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
                    <td>{{ $item['duration'] }}</td>
                    <td>{{ $item['createdAt'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
