<div id="layoutSidenav">

    <!-- Include the Sidebar -->
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
                        <select class="form-select" id="status" name="duration">
                            <option disabled>Select Duration</option>
                            <option value="1 Month">1 Month
                            </option>
                            <option value="3 Months">3 Months</option>
                            <option value="12 Months">12 Months
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pbt" class="form-label">PBT</label>
                        <select class="form-select" id="pbt" name="pbt">
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
                        <select class="form-select" id="location" name="location">
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
                            step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-5" style="float: right">Create</button>
                </form>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
