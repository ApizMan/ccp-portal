<form action="{{ route('auth.set-password') }}" method="post">
    @csrf
    <center style="margin-top: 100px;">
        <div class="card" style="width: 30rem; padding-top: 50px; padding-bottom: 20px;">
            <img src="{{ $logo }}" alt="CCP Logo" style="padding-left: 20%; padding-right: 20%;">
            <div class="card-body">
                <h3 class="card-title">Request Password</h3>
                <br>
                <div class="form-group" style="text-align: left;">
                    <label for="exampleInputPassword1">Email</label>
                    <div class="input-group mb-5">
                        <input type="text" class="form-control" name="email" placeholder="Staff's email"
                            aria-label="Staff's username" aria-describedby="basic-addon2">

                        <select class="input-group-text" name="type_email" id="type_email">
                            <option value="@raisevest.com.my">@raisevest.com.my</option>
                            <option value="@vistasummerose.com.my">@vistasummerose.com.my</option>
                            <option value="@vista-summerose.com.my">@vista-summerose.com.my</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mb-3">Request</button>
                <br>
                <a href="{{ route('auth.login') }}" style="text-decoration: none;">Login</a>
            </div>
        </div>
    </center>
</form>
