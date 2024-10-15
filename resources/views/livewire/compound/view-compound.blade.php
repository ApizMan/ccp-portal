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
                        <h1 class="mt-4">View Compound</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">
                                {{ $data['noticeNo'] }}
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username"
                        value="{{ $data['user']['firstName'] }} {{ $data['user']['secondName'] }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="plateNumber" class="form-label">Plate Number</label>
                    <input type="text" name="plateNumber" class="form-control" id="plateNumber"
                        value="{{ $data['vehicleRegistrationNumber'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="noticeNo" class="form-label">Notice No.</label>
                    <input type="text" name="noticeNo" class="form-control" id="noticeNo"
                        value="{{ $data['noticeNo'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="receiptNo" class="form-label">Receipt No.</label>
                    <input type="text" name="receiptNo" class="form-control" id="receiptNo"
                        value="{{ $data['receiptNo'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="paymentDate" class="form-label">Payment Date</label>
                    <input type="text" name="paymentDate" class="form-control" id="paymentDate"
                        value="{{ $data['paymentDate'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="PaidAmount" class="form-label">Amount (RM)</label>
                    <input type="text" name="PaidAmount" class="form-control" id="PaidAmount"
                        value="{{ number_format($data['paidAmount'], 2) }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="paymentLocation" class="form-label">Payment Method</label>
                    <input type="text" name="paymentLocation" class="form-control" id="paymentLocation"
                        value="{{ $data['paymentLocation'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <input type="text" name="notes" class="form-control" id="notes"
                        value="{{ $data['notes'] }}" readonly>
                </div>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
