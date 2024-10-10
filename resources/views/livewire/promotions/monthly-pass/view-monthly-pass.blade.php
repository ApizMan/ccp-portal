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
                        <h1 class="mt-4">View Promotion Monthly Pass</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">
                                {{ $data['title'] }}
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" id="title"
                        value="{{ $data['title'] }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" id="description"
                        value="{{ $data['description'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Launching Date</label>
                    <input type="text" name="date" class="form-control" id="date" value="{{ $data['date'] }}"
                        readonly>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image Promotion</label>
                    <a href="{{ $data['image'] }}" target="_blank" class="form-control"
                        style="text-decoration: none; color: rgb(0, 67, 251);" rel="noopener noreferrer">{{ $data['image'] }}</a>
                </div>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
