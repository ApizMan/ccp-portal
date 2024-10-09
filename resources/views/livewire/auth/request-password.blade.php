<form action="{{ route('auth.set-password') }}" method="post">
    @csrf
    <center style="margin-top: 100px;">
        <div class="card" style="width: 25rem; padding-top: 50px; padding-bottom: 20px;">
            <img src="{{ $logo }}" alt="CCP Logo" style="padding-left: 20%; padding-right: 20%;">
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
