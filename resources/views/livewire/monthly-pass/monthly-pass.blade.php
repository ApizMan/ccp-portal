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
                        <h1 class="d-inline-block">Monthly Pass</h1>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active">City Car Park</li>
                        </ol>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <a class="btn btn-outline-success"
                                href="{{ route('monthlyPass.monthlyPass.export_excel') }}" role="button">Export
                                Excel</a>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-outline-danger" href="{{ route('monthlyPass.monthlyPass.export_pdf') }}"
                                role="button">Export PDF</a>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-warning" href="{{ route('monthlyPass.monthly_pass_create') }}"
                                role="button">Create New Monthly Pass</a>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Monthly Pass Records
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                @if (count($datas) > 0)
                                <tr>
                                    <th>No. Receipt</th>
                                    <th>Name</th>
                                    <th>Plate Number</th>
                                    <th>PBT</th>
                                    <th>Location</th>
                                    <th>Amount (RM)</th>
                                    <th>Promotion Event</th>
                                    <th>Duration</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                @endif
                            </thead>
                            <tbody>
                                @if (count($datas) > 0)
                                @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $data['noReceipt'] }}</td>
                                    <td>{{ $data['user']['firstName'] }} {{ $data['user']['secondName'] }}</td>
                                    <td>{{ $data['plateNumber'] }}</td>
                                    <td>{{ $data['pbt'] }}</td>
                                    <td>{{ $data['location'] }}</td>
                                    <td>{{ number_format($data['amount'], 2) }}</td>
                                    <td>{{ $data['event']['title'] ?? 'No Event' }}</td>
                                    <td>{{ $data['duration'] }}</td>
                                    <td>{{ $data['createdAt'] }}</td>
                                    <td>
                                        <div class="d-flex mx-3 " style="gap: 10px;">
                                            <a class="btn btn-primary"
                                                href="{{ route('monthlyPass.monthly_pass_edit', $data['id']) }}"
                                                role="button"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{ route('monthlyPass.monthly_pass_delete', $data['id']) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirmDelete();">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" role="button">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                            <script>
                                                function confirmDelete() {
                                                            return confirm("Are you sure you want to delete this item?");
                                                        }
                                            </script>
                                        </div>
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