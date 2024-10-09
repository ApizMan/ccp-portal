<head>
    <style>
        .pegeypay-step,
        .fpx-step {
            counter-reset: section;
            list-style: none;
        }

        .pegeypay-step li,
        .fpx-step li {
            margin: 0 0 10px 0;
            line-height: 40px;
        }

        .pegeypay-step li:before,
        .fpx-step li:before {
            content: counter(section);
            counter-increment: section;
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: 0 20px 0 0;
            border: 1px solid #ccc;
            border-radius: 100%;
            text-align: center;
        }
    </style>

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
                <h1 class="mt-4">Settings</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">If the database have been reset, please refer below:</li>
                </ol>
            </div>
            <div class="container">
                <div class="row">
                    <!-- Pegeypay Card Column -->
                    <div class="col-md-6 col-12">
                        <div class="card mx-2 mb-4" style="width: 100%;">
                            <div class="card-body">
                                <h4 class="card-title">Pegeypay</h4>
                                <h6 class="card-subtitle mb-4 text-body-secondary">To reset the QR of Pegeypay, follow
                                    the steps below:</h6>
                                <ul class="pegeypay-step">
                                    <li>Key-in the Onboarding Key and press send button:</li>
                                    <div class="mx-5">
                                        <form action="{{ route('setting.setting_pegeypay') }}" method="POST">
                                            @csrf
                                            <div class="input-group mb-3">
                                                <input type="text" name="onboarding_key" class="form-control"
                                                    placeholder="Enter Onboarding Key" required>
                                                <button class="btn btn-outline-primary" type="submit"
                                                    id="button-addon2">Send</button>

                                            </div>
                                        </form>
                                    </div>
                                    <li>The access code on database need to be refresh, click the button below:</li>
                                    <div class="mx-5">
                                        <form action="{{ route('setting.setting_pegeypay_refresh') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-primary" type="submit">Refresh Access Code</button>
                                        </form>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- FPX Card Column -->
                    <div class="col-md-6 col-12">
                        <div class="card mx-2 mb-4" style="width: 100%;">
                            <div class="card-body">
                                <h4 class="card-title">FPX</h4>
                                <h6 class="card-subtitle mb-4 text-body-secondary">To reset the FPX, follow the steps
                                    below:</h6>
                                <ul class="fpx-step">
                                    <li>If the FPX not function, click the button below:</li>
                                    <div class="mx-5">
                                        <form action="{{ route('setting.setting_fpx_refresh') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-primary" type="submit">Refresh FPX</button>
                                        </form>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Column -->
                <div class="card mx-2 mb-4" style="width: 100%;">
                    <div class="card-body">
                        <h4 class="card-title">Change Password</h4>
                        <h6 class="card-subtitle mb-4 text-body-secondary">You can change your password here:</h6>
                        <form action="{{ route('setting.change_password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">New Password</span>
                                <input type="password" name="newPassword" class="form-control"
                                    placeholder="Enter new password" aria-label="newPassword"
                                    aria-describedby="basic-addon1" required>
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Confirm Password</span>
                                <input type="password" name="newPassword_confirmation" class="form-control"
                                    placeholder="Enter confirm new password" aria-label="newPassword_confirmation"
                                    aria-describedby="basic-addon1" required>
                            </div>

                            <button type="submit" class="btn btn-primary" style="float: right;">Change</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </div>
</div>
