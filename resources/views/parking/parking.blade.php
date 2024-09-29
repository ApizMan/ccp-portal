<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Parking Data</h1>

        @if (!empty($decodedData) && is_array($decodedData))
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Price</th>
                        <!-- Add other headers based on your data structure -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($decodedData as $item)
                        <tr>
                            <td>{{ $item['id'] }}</td>
                            <!-- Add other data fields based on your data structure -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available.</p>
        @endif
    </div>
</body>

</html>
