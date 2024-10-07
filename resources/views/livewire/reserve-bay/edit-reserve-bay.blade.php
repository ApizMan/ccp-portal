<head>
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
</head>

<div id="layoutSidenav">

    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Reserve Bay</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">{{ $data['companyName'] }}</li>
                </ol>
                <form class="mx-5 my-5" action="{{ route('reserveBay.reserve_bay_update', ['id' => $reserveBayId]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <select class="form-select" id="username" name="userId">
                            <option disabled>Select Username</option>
                            @foreach ($data_users as $user)
                                <option @if ($data['user']['id'] == $user['id']) selected @endif value="{{ $user['id'] }}">
                                    {{ $user['firstName'] }} {{ $user['secondName'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Company Name</label>
                        <input type="text" name="companyName" class="form-control" id="companyName"
                            value="{{ $data['companyName'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="companyRegistration" class="form-label">Company Registration Number</label>
                        <input type="text" name="companyRegistration" class="form-control" id="companyRegistration"
                            value="{{ $data['companyRegistration'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="businessType" class="form-label">Business Type</label>
                        <select name="businessType" id="businessType" class="form-select">
                            <option disabled>Select Business Type</option>
                            <option value="Klinik Swasta" @if ($data['businessType'] == 'Klinik Swasta') selected @endif>Klinik
                                Swasta</option>
                            <option value="Agensi Kerajaan" @if ($data['businessType'] == 'Agensi Kerajaan') selected @endif>Agensi
                                Kerajaan</option>
                            <option value="Bank / Institusi Kewangan" @if ($data['businessType'] == 'Bank / LinkedInusi Kewangan') selected @endif>
                                Bank / Institusi Kewangan</option>
                            <option value="Kilang" @if ($data['businessType'] == 'Kilang') selected @endif>Kilang</option>
                            <option value="Kedai Membaiki Kenderaan / Motorsikal"
                                @if ($data['businessType'] == 'Kedai Membaiki Kenderaan / Motorsikal') selected @endif>Kedai Membaiki Kenderaan / Motorsikal
                            </option>
                            <option value="Industri Kecil / Sederhana"
                                @if ($data['businessType'] == 'Industri Kecil / Sederhana') selected @endif>Industri Kecil / Sederhana</option>
                            <option value="Hotel Bajet" @if ($data['businessType'] == 'Hotel Bajet') selected @endif>Hotel Bajet
                            </option>
                            <option value="Lain - Lain" @if ($data['businessType'] == 'Lain - Lain') selected @endif>Lain - Lain
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="address1" class="form-label">Address 1</label>
                        <input type="text" name="address1" class="form-control" id="address1"
                            value="{{ $data['address1'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="address2" class="form-label">Address 2</label>
                        <input type="text" name="address2" class="form-control" id="address2"
                            value="{{ $data['address2'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="address3" class="form-label">Address 3</label>
                        <input type="text" name="address3" class="form-control" id="address3"
                            value="{{ $data['address3'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="postcode" class="form-label">Postcode</label>
                        <input type="text" name="postcode" class="form-control" id="postcode"
                            value="{{ $data['postcode'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" id="city"
                            value="{{ $data['city'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" name="state" class="form-control" id="state"
                            value="{{ $data['state'] }}">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <select class="form-select" id="location" name="location">
                            <option disabled>Select Location</option>
                            @foreach ($data_pbt as $pbt)
                                <option @if ($data['location'] == $pbt['description']) selected @endif
                                    value="{{ $pbt['description'] }}">
                                    {{ $pbt['description'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="picFirstName" class="form-label">PIC First Name</label>
                        <input type="text" name="picFirstName" class="form-control" id="picFirstName"
                            value="{{ $data['picFirstName'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="picLastName" class="form-label">PIC Last Name</label>
                        <input type="text" name="picLastName" class="form-control" id="picLastName"
                            value="{{ $data['picLastName'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <input type="text" name="phoneNumber" class="form-control" id="phoneNumber"
                            value="{{ $data['phoneNumber'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" class="form-control" id="email"
                            value="{{ $data['email'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="idNumber" class="form-label">ID Number</label>
                        <input type="text" name="idNumber" class="form-control" id="idNumber"
                            value="{{ $data['idNumber'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="totalLotRequired" class="form-label">Total Lot Required</label>
                        <input type="number" name="totalLotRequired" class="form-control" id="totalLotRequired"
                            step="1" min="0" value="{{ $data['totalLotRequired'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason</label>
                        <input type="text" name="reason" class="form-control" id="reason"
                            value="{{ $data['reason'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="lotNumber" class="form-label">Lot Number</label>
                        <input type="text" name="lotNumber" class="form-control" id="lotNumber" min="0"
                            step="1" value="{{ $data['lotNumber'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="designatedBayPicture" class="form-label">Designated Bay Picture</label>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="designatedBayPicture">Upload</label>
                            <input type="file" class="form-control" id="designatedBayPicture"
                                name="designatedBayPicture" value="{{ $data['designatedBayPicture'] }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="registerNumberPicture" class="form-label">Register Number Picture</label>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="registerNumberPicture">Upload</label>
                            <input type="file" class="form-control" id="registerNumberPicture"
                                name="registerNumberPicture" value="{{ $data['registerNumberPicture'] }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="idCardPicture" class="form-label">ID Card Picture</label>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="idCardPicture">Upload</label>
                            <input type="file" class="form-control" id="idCardPicture" name="idCardPicture"
                                value="{{ $data['idCardPicture'] }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mb-5" style="float: right">Update</button>

                    <script>
                        const lotNumberInput = document.getElementById('lotNumber');
                        const totalLotRequiredInput = document.getElementById('totalLotRequired');

                        // Prevent decimal values
                        lotNumberInput.addEventListener('input', function(e) {
                            // Remove any decimal or non-numeric characters
                            e.target.value = e.target.value.replace(/[^0-9]/g, '');
                        });

                        // Prevent decimal values
                        totalLotRequiredInput.addEventListener('input', function(e) {
                            // Remove any decimal or non-numeric characters
                            e.target.value = e.target.value.replace(/[^0-9]/g, '');
                        });
                    </script>
                </form>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
