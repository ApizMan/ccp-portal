<!DOCTYPE html>
<html>

<head>
    <title>Reserve Bay Data</title>
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
    <h1>Reserve Bay Records</h1>
    <table>
        <thead>
            <tr>
                <th>Company Name</th>
                <th>Business Registration</th>
                <th>Address</th>
                <th>PIC Name</th>
                <th>Phone Number</th>
                <th>Total Lot Required</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item['companyName'] }}</td>
                    <td>{{ $item['companyRegistration'] }}</td>
                    <td>{{ $item['address1'] }}, {{ $item['address2'] }}, {{ $item['address3'] }},
                        {{ $item['postcode'] }}, {{ $item['city'] }}, {{ $item['state'] }}, {{ $item['location'] }}</td>
                    <td>{{ $item['picFirstName'] }} {{ $item['picLastName'] }}</td>
                    <td>{{ $item['phoneNumber'] }}</td>
                    <td>{{ $item['totalLotRequired'] }}</td>
                    <td>{{ $item['status'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
