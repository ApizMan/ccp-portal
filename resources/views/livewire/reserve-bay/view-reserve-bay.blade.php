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
    <style>
        .rectangle {
            width: 100px;
            height: 30px;
            border-radius: 50px;
        }
    </style>
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
                        <h1 class="mt-4">View Reserve Bay</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">
                                <div class="container px-4 text-center">
                                    <div class="row">
                                        <div class="col">
                                            {{ $data['companyName'] }}
                                        </div>
                                        <div class="col">
                                            <div class="rectangle"
                                                style="background-color: {{ $data['status'] == 'PENDING' ? '#FFE600FF' : ($data['status'] == 'APPROVED' ? '#2BFF00FF' : '#F90000FF') }}; color: {{ $data['status'] == 'PENDING' ? '#000000FF' : ($data['status'] == 'APPROVED' ? '#000000FF' : '#FFFFFFFF') }}">
                                                {{ $data['status'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                    @if ($data['status'] == 'PENDING')
                        <div class="d-flex mx-3 " style="gap: 10px;">
                            <form
                                action="{{ route('reserveBay.reserve_bay_update_status_approve_view', $reserveBayId) }}"
                                method="post">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success" role="button">Approve</button>
                            </form>

                            <form
                                action="{{ route('reserveBay.reserve_bay_update_status_reject_view', $reserveBayId) }}"
                                method="post">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning" role="button">Reject</button>
                            </form>

                        </div>
                    @endif

                </div>

                <form class="mx-5 my-5" action="{{ route('reserveBay.reserve_bay_update', ['id' => $reserveBayId]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="companyName" class="form-control" id="companyName"
                            value="{{ $data['user']['firstName'] }} {{ $data['user']['secondName'] }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Company Name</label>
                        <input type="text" name="companyName" class="form-control" id="companyName"
                            value="{{ $data['companyName'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="companyRegistration" class="form-label">Company Registration Number</label>
                        <input type="text" name="companyRegistration" class="form-control" id="companyRegistration"
                            value="{{ $data['companyRegistration'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="businessType" class="form-label">Business Type</label>
                        <input type="text" name="businessType" class="form-control" id="businessType"
                            value="{{ $data['businessType'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="address1" class="form-label">Address 1</label>
                        <input type="text" name="address1" class="form-control" id="address1"
                            value="{{ $data['address1'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="address2" class="form-label">Address 2</label>
                        <input type="text" name="address2" class="form-control" id="address2"
                            value="{{ $data['address2'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="address3" class="form-label">Address 3</label>
                        <input type="text" name="address3" class="form-control" id="address3"
                            value="{{ $data['address3'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" name="postcode" class="form-control" id="postcode"
                            value="{{ $data['postcode'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" id="city"
                            value="{{ $data['city'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" name="state" class="form-control" id="state"
                            value="{{ $data['state'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" id="location"
                            value="{{ $data['location'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="picFirstName" class="form-label">PIC First Name</label>
                        <input type="text" name="picFirstName" class="form-control" id="picFirstName"
                            value="{{ $data['picFirstName'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="picLastName" class="form-label">PIC Last Name</label>
                        <input type="text" name="picLastName" class="form-control" id="picLastName"
                            value="{{ $data['picLastName'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <input type="text" name="phoneNumber" class="form-control" id="phoneNumber"
                            value="{{ $data['phoneNumber'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email"
                            value="{{ $data['email'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="idNumber" class="form-label">ID Number</label>
                        <input type="text" name="idNumber" class="form-control" id="idNumber"
                            value="{{ $data['idNumber'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="totalLotRequired" class="form-label">Total Lot Required</label>
                        <input type="number" name="totalLotRequired" class="form-control" id="totalLotRequired"
                            step="1" min="0" value="{{ $data['totalLotRequired'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control" id="reason"
                            value="{{ $data['reason'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="lotNumber" class="form-label">Lot Number</label>
                        <input type="text" name="lotNumber" class="form-control" id="lotNumber" min="0"
                            step="1" value="{{ $data['lotNumber'] }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="designatedBayPicture" class="form-label">Designated Bay Picture</label>
                        <a href="{{ $data['designatedBayPicture'] }}" target="_blank" class="form-control"
                            rel="noopener noreferrer">View More</a>
                    </div>

                    <div class="mb-3">
                        <label for="registerNumberPicture" class="form-label">Register Number Picture</label>
                        <a href="{{ $data['registerNumberPicture'] }}" target="_blank" class="form-control"
                            rel="noopener noreferrer">View More</a>
                    </div>

                    <div class="mb-3">
                        <label for="idCardPicture" class="form-label">ID Card Picture</label>
                        <a href="{{ $data['idCardPicture'] }}" target="_blank" class="form-control"
                            rel="noopener noreferrer">View More</a>
                    </div>
                </form>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
