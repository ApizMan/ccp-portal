<div id="layoutSidenav">
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Create New Monthly Pass</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">City Car Park</li>
                </ol>
                <form class="mx-5 my-5" action="{{ route('monthlyPass.monthly_pass_store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <select class="form-select" id="username" name="userId">
                            <option disabled>Select Username</option>
                            @foreach ($data_users as $user)
                                <option value="{{ $user['id'] }}">
                                    {{ $user['firstName'] }} {{ $user['secondName'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="plateNumber" class="form-label">Plate Number</label>
                        <input type="text" name="plateNumber" class="form-control" id="plateNumber" required>
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration</label>
                        <select class="form-select" id="duration" name="duration" onchange="syncAmount()" required>
                            <option hidden>Select Duration</option>
                            <option value="1 Month">1 Month</option>
                            <option value="3 Months">3 Months</option>
                            <option value="12 Months">12 Months</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pbt" class="form-label">PBT</label>
                        <select class="form-select" id="pbt" name="pbt" onchange="syncPBT()">
                            <option disabled>Select PBT</option>
                            @foreach ($data_pbt as $pbt)
                                <option value="{{ $pbt['name'] }}">
                                    {{ $pbt['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <select class="form-select" id="location" name="location" onchange="syncLocation()">
                            <option disabled>Select Location</option>
                            @foreach ($data_pbt as $pbt)
                                <option value="{{ $pbt['description'] }}">
                                    {{ $pbt['description'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" id="amount" min="0"
                            step="0.01" readonly required>
                    </div>

                    <div class="mb-3">
                        <label for="promotionId" class="form-label">Promotion</label>
                        <select class="form-select" id="promotionId" name="promotionId">
                            <option hidden>Select Promotion</option>
                            @if (count($data_promotions) > 0)
                                @foreach ($data_promotions as $promotion)
                                    @if (
                                        \Carbon\Carbon::now()->between(
                                            \Carbon\Carbon::parse($promotion['date']),
                                            \Carbon\Carbon::parse($promotion['expiredDate'])))
                                        <option value="{{ $promotion['id'] }}">
                                            {{ $promotion['title'] }}
                                        </option>
                                    @endif
                                @endforeach
                            @else
                                <option disabled>No Promotion Available</option>
                            @endif

                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-5" style="float: right">Create</button>
                </form>
            </div>
        </main>
        @include('layouts.footer')
    </div>
</div>

<script>
    // Define a map of durations and corresponding base amounts
    const durationAmountMap = {
        '1 Month': 79.50,
        '3 Months': 207.00,
        '12 Months': 667.80
    };

    // Define a map of PBTs and corresponding locations
    const pbtLocationMap = {
        'PBT Machang': 'Kelantan',
        'PBT Kuantan': 'Pahang',
        'PBT Kuala Terengganu': 'Terengganu',
    };

    // Reverse map to get PBT by location
    const locationPbtMap = Object.fromEntries(
        Object.entries(pbtLocationMap).map(([pbt, location]) => [location, pbt])
    );

    // Define a map of promotion IDs and corresponding rates
    const promotionRateMap = {
        @foreach ($data_promotions as $promotion)
            '': '',
            '{{ $promotion['id'] }}': parseFloat('{{ $promotion['rate'] }}'),
        @endforeach
    };

    console.log('Promotion Rate Map:', promotionRateMap); // Log the map for debugging

    // Function to sync the amount with the selected duration and promotion rate
    // Function to sync the amount with the selected duration and promotion rate
    function syncAmount() {
        const duration = document.getElementById('duration').value;
        const amountInput = document.getElementById('amount');
        const promotionId = document.getElementById('promotionId').value;

        // Get the base amount from durationAmountMap
        let baseAmount = parseFloat(durationAmountMap[duration]) || 0;

        // Get the promotion rate, defaulting to 0 if not found
        let promotionRate = promotionRateMap[promotionId] || 0; // Use 0 if promotionId is not found

        // Calculate the discount amount
        const discountAmount = (promotionRate / 100) * baseAmount;

        // Log the values for debugging
        console.log('Base Amount:', baseAmount);
        console.log('Selected Promotion ID:', promotionId);
        console.log('Promotion Rate:', promotionRate, '%');
        console.log('Discount Amount:', discountAmount);

        // Subtract the discount amount from the base amount
        let finalAmount = baseAmount - discountAmount;

        // Ensure the final amount is not less than 0
        if (finalAmount < 0) {
            finalAmount = 0;
        }

        // Set the amount input value
        amountInput.value = finalAmount.toFixed(2);
    }


    // Function to sync the location based on the selected PBT
    function syncPBT() {
        const pbt = document.getElementById('pbt').value;
        const locationSelect = document.getElementById('location');

        // Clear existing options in the location select
        locationSelect.innerHTML = '<option disabled>Select Location</option>';

        if (pbt in pbtLocationMap) {
            const location = pbtLocationMap[pbt];

            // Create a new option for the selected location
            const option = document.createElement('option');
            option.value = location;
            option.textContent = location;
            locationSelect.appendChild(option);

            // Automatically select the location
            locationSelect.value = location;
        }
    }

    // Function to sync the PBT based on the selected location
    function syncLocation() {
        const location = document.getElementById('location').value;
        const pbtSelect = document.getElementById('pbt');

        // Clear existing options in the PBT select
        pbtSelect.innerHTML = '<option disabled>Select PBT</option>';

        if (location in locationPbtMap) {
            const pbt = locationPbtMap[location];

            // Create a new option for the selected PBT
            const option = document.createElement('option');
            option.value = pbt;
            option.textContent = pbt;
            pbtSelect.appendChild(option);

            // Automatically select the PBT
            pbtSelect.value = pbt;
        }
    }

    // Function to sync the amount based on the selected promotion
    function syncPromotion() {
        syncAmount();
    }

    // Attach event listeners to the dropdowns to trigger amount calculation
    document.getElementById('promotionId').addEventListener('change', syncPromotion);
    document.getElementById('duration').addEventListener('change', syncAmount);
</script>
