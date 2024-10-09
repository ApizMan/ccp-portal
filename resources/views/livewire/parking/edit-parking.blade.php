<div id="layoutSidenav">

    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Parking</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">{{ $data['plateNumber'] }}</li>
                </ol>
                <form class="mx-5 my-5"
                    action="{{ route('parking.parking_update', ['id' => $parkingId, 'transactionId' => $data['transaction']['id']]) }}"
                    method="POST">
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
                        <label for="plateNumber" class="form-label">Plate Number</label>
                        <input type="text" name="plateNumber" class="form-control" id="plateNumber"
                            value="{{ $data['plateNumber'] }}">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option disabled>Select Status</option>
                            <option value="completed" @if ($data['transaction']['status'] == 'completed') selected @endif>Completed
                            </option>
                            <option value="pending" @if ($data['transaction']['status'] == 'pending') selected @endif>Pending</option>
                            <option value="cenceled" @if ($data['transaction']['status'] == 'canceled') selected @endif>Canceled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pbt" class="form-label">PBT</label>
                        <select class="form-select" id="pbt" name="pbt">
                            <option disabled>Select PBT</option>
                            @foreach ($data_pbt as $pbt)
                                <option @if ($data['pbt'] == $pbt['name']) selected @endif value="{{ $pbt['name'] }}">
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
                                <option @if ($data['location'] == $pbt['description']) selected @endif
                                    value="{{ $pbt['description'] }}">
                                    {{ $pbt['description'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" id="amount"
                            value="{{ number_format($data['transaction']['amount'], 2) }}" min="0"
                            step="0.01">
                    </div>
                    <button type="submit" class="btn btn-primary mb-5" style="float: right">Update</button>
                </form>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
