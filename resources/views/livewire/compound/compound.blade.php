<head>
    <script>
        // Hide the error alert after 10 seconds (10,000 ms)
        setTimeout(function() {
            var errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.style.display = 'none';
            }

            var statusAlert = document.getElementById('status-alert');
            if (statusAlert) {
                statusAlert.style.display = 'none';
            }
        }, 5000); // 10000 milliseconds = 5 seconds
    </script>
</head>

<div id="layoutSidenav">


    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="status-alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" id="error-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <main>
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                    <div>
                        <h1 class="mt-4">Compound</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">City Car Park</li>
                        </ol>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <a class="btn btn-outline-success" href="{{ route('compound.compound.export_excel') }}"
                                role="button">Export Excel</a>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-outline-danger" href="{{ route('compound.compound.export_pdf') }}"
                                role="button">Export PDF</a>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Compound Records
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                @if (count($datas) > 0)
                                    <tr>
                                        <th>Name</th>
                                        <th>Plate Number</th>
                                        <th>Notice No.</th>
                                        <th>Receipt No.</th>
                                        <th>Amount (RM)</th>
                                        <th>Payment Method</th>
                                        <th>Payment Date</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody>
                                @if (count($datas) > 0)
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td><a href="{{ route('compound.compound_public_view', $data['id']) }}"
                                                    style="color: black; text-decoration: none;">{{ $data['user']['firstName'] }}
                                                    {{ $data['user']['secondName'] }}</a></td>
                                            <td><a href="{{ route('compound.compound_public_view', $data['id']) }}"
                                                    style="color: black; text-decoration: none;">{{ $data['vehicleRegistrationNumber'] }}</a>
                                            </td>
                                            <td><a href="{{ route('compound.compound_public_view', $data['id']) }}"
                                                    style="color: black; text-decoration: none;">{{ $data['noticeNo'] }}</a>
                                            </td>
                                            <td><a href="{{ route('compound.compound_public_view', $data['id']) }}"
                                                    style="color: black; text-decoration: none;">{{ $data['receiptNo'] }}</a>
                                            </td>
                                            <td><a href="{{ route('compound.compound_public_view', $data['id']) }}"
                                                    style="color: black; text-decoration: none;">{{ number_format($data['paidAmount'], 2) }}</a>
                                            </td>
                                            <td><a href="{{ route('compound.compound_public_view', $data['id']) }}"
                                                    style="color: black; text-decoration: none;">{{ $data['paymentLocation'] }}</a>
                                            </td>
                                            <td><a href="{{ route('compound.compound_public_view', $data['id']) }}"
                                                    style="color: black; text-decoration: none;">{{ $data['paymentDate'] }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">No data found</td>
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
