<form action="{{ route('auth.login.post') }}" method="post">
    @csrf
    <center style="margin-top: 100px;">
        <div class="card" style="width: 25rem; padding-top: 50px; padding-bottom: 20px;">
            <img src="{{ $logo }}" alt="CCP Logo" style="padding-left: 20%; padding-right: 20%;">
            <div class="card-body">
                <h3 class="card-title">Login</h3>
                <br>
                <div class="form-group" style="text-align: left;">
                    <label for="exampleInputPassword1">Email</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="email" placeholder="Staff's email"
                            aria-label="Staff's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">@raisevest.com.my</span>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-5" style="text-align: left;">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                        placeholder="Password">
                    <a href="{{ route('auth.request-password') }}" style="float: right; text-decoration: none;">I
                        don't have password</a>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </div>
    </center>
</form>
