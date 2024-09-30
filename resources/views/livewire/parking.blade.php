<head>
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
</head>

<div id="layoutSidenav">

    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Parking</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">City Car Park</li>
                </ol>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        DataTable Example
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
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
                                @if (count($datas) > 0)
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td>{{ $data['user']['firstName'] }} {{ $data['user']['secondName'] }}</td>
                                            <td>{{ $data['plateNumber'] }}</td>
                                            <td>{{ $data['pbt'] }}</td>
                                            <td>{{ $data['location'] }}</td>
                                            <td>{{ number_format($data['transaction']['amount'], 2) }}</td>
                                            <td>{{ strtoupper($data['transaction']['status']) }}</td>
                                            <td>{{ $data['createdAt'] }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No data found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
