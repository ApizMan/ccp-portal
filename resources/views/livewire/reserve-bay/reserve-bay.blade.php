<head>
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
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
                        <h1 class="d-inline-block">Reserve Bay</h1>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active">City Car Park</li>
                        </ol>
                    </div>
                    <a class="btn btn-warning" href="{{ route('reserveBay.reserve_bay_create') }}" role="button">Create
                        New Reserve Bay</a>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Reserve Bay Records
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                @if (count($datas) > 0)
                                    <tr>
                                        <th>
                                            <p style="text-align: center">Company Name</p>
                                        </th>
                                        <th>
                                            <p style="text-align: center">Business Registration</p>
                                        </th>
                                        <th>
                                            <p style="text-align: center">Business Type</p>
                                        </th>
                                        <th>
                                            <p style="text-align: center">PIC Name</p>
                                        </th>
                                        <th>
                                            <p style="text-align: center">Location</p>
                                        </th>
                                        <th>
                                            <p style="text-align: center">Created At</p>
                                        </th>
                                        <th>
                                            <p style="text-align: center">Status</p>
                                        </th>
                                        <th>
                                            <p style="text-align: center">Action</p>
                                        </th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody>
                                @if (count($datas) > 0)
                                    @foreach ($datas as $data)
                                        <tr>
                                            @if ($data['status'] == 'PENDING')
                                                <td>{{ $data['companyName'] }}</td>
                                            @elseif ($data['status'] == 'APPROVED')
                                                <td>
                                                    <p class="text-success">{{ $data['companyName'] }}</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-danger">{{ $data['companyName'] }}</p>
                                                </td>
                                            @endif

                                            @if ($data['status'] == 'PENDING')
                                                <td>{{ $data['companyRegistration'] }}</td>
                                            @elseif ($data['status'] == 'APPROVED')
                                                <td>
                                                    <p class="text-success">{{ $data['companyRegistration'] }}</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-danger">{{ $data['companyRegistration'] }}</p>
                                                </td>
                                            @endif

                                            @if ($data['status'] == 'PENDING')
                                                <td>{{ $data['businessType'] }}</td>
                                            @elseif ($data['status'] == 'APPROVED')
                                                <td>
                                                    <p class="text-success">{{ $data['businessType'] }}</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-danger">{{ $data['businessType'] }}</p>
                                                </td>
                                            @endif

                                            @if ($data['status'] == 'PENDING')
                                                <td>{{ $data['picFirstName'] }} {{ $data['picLastName'] }}</td>
                                            @elseif ($data['status'] == 'APPROVED')
                                                <td>
                                                    <p class="text-success">{{ $data['picFirstName'] }}
                                                        {{ $data['picLastName'] }}</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-danger">{{ $data['picFirstName'] }}
                                                        {{ $data['picLastName'] }}</p>
                                                </td>
                                            @endif

                                            @if ($data['status'] == 'PENDING')
                                                <td>{{ $data['location'] }}</td>
                                            @elseif ($data['status'] == 'APPROVED')
                                                <td>
                                                    <p class="text-success">{{ $data['location'] }}</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-danger">{{ $data['location'] }}</p>
                                                </td>
                                            @endif

                                            @if ($data['status'] == 'PENDING')
                                                <td>{{ $data['createdAt'] }}</td>
                                            @elseif ($data['status'] == 'APPROVED')
                                                <td>
                                                    <p class="text-success">{{ $data['createdAt'] }}</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-danger">{{ $data['createdAt'] }}</p>
                                                </td>
                                            @endif
                                            @if ($data['status'] == 'PENDING')
                                                <td>{{ $data['status'] }}</td>
                                            @elseif ($data['status'] == 'APPROVED')
                                                <td>
                                                    <p class="text-success">{{ $data['status'] }}</p>
                                                </td>
                                            @else
                                                <td>
                                                    <p class="text-danger">{{ $data['status'] }}</p>
                                                </td>
                                            @endif

                                            <td>
                                                <div class="d-flex mx-3 " style="gap: 10px;">

                                                    @if ($data['status'] == 'PENDING')
                                                        <div class="d-flex mx-3 " style="gap: 10px;">
                                                            <form
                                                                action="{{ route('reserveBay.reserve_bay_update_status_approve', $data['id']) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-success"
                                                                    role="button">Approve</button>
                                                            </form>

                                                            <form
                                                                action="{{ route('reserveBay.reserve_bay_update_status_reject', $data['id']) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-warning"
                                                                    role="button">Reject</button>
                                                            </form>

                                                        </div>
                                                    @else
                                                        <div class="d-flex mx-3 " style="width: 100%;">
                                                        </div>
                                                    @endif

                                                    <a class="btn btn-primary"
                                                        href="{{ route('reserveBay.reserve_bay_edit', $data['id']) }}"
                                                        role="button"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <form
                                                        action="{{ route('reserveBay.reserve_bay_delete', $data['id']) }}"
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
