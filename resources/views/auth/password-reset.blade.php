<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    @php
        include_once app_path('constants.php');
        $logo = CCP_LOGO;
    @endphp
</head>

<body>
    <center style="margin-top: 100px;">
        <div class="card" style="width: 25rem; padding-top: 50px; padding-bottom: 20px;">
            <img src="{{ $logo }}" alt="CCP Logo"
                style="padding-left: 20%; padding-right: 20%; width: 150px; height: 100px;">
            <div class="card-body">
                <h1>Your New Password</h1>
                <p>Your new password is: <strong>{{ $password }}</strong></p>
                <p>Please change it after logging in.</p>
            </div>
        </div>
    </center>
</body>

</html>
