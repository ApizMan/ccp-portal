<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
</head>

<body style="background-color: #d6eaf8;">
    <form action="{{ route('auth.set-password') }}" method="post">
        @csrf
        <center style="margin-top: 100px;">
            <div class="card" style="width: 25rem; padding-top: 50px; padding-bottom: 20px;">
                <img src="{{ config('constants.CCP_LOGO') }}" alt="CCP Logo"
                    style="padding-left: 20%; padding-right: 20%;">
                <div class="card-body">
                    <h3 class="card-title">Request Password</h3>
                    <br>
                    <div class="form-group" style="text-align: left;">
                        <label for="exampleInputPassword1">Email</label>
                        <div class="input-group mb-5">
                            <input type="text" class="form-control" name="email" placeholder="Staff's email"
                                aria-label="Staff's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">@raisevest.com.my</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Request</button>
                    <br>
                    <a href="{{ route('auth.login') }}" style="text-decoration: none;">Login</a>
                </div>
            </div>
        </center>
    </form>

</body>

</html>
