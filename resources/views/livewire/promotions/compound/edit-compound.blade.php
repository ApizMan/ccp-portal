<div id="layoutSidenav">

    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Promotion Compound</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">{{ $data['title'] }}</li>
                </ol>
                <form class="mx-5 my-5" action="{{ route('promotion.compound.compound_update', $data['id']) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title"
                            value="{{ $data['title'] }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" id="description"
                            value="{{ $data['description'] }}">
                    </div>
                    <div class="mb-3">
                        <label for="rate" class="form-label">Rate</label>
                        <input type="number" name="rate" class="form-control" id="rate"
                            value="{{ $data['rate'] }}" min="0" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="rate" class="form-label">Time Can Be Use</label>
                        <select name="frequency" id="frequency" class="form-select">
                            <option disabled>Select Time Can Be Used</option>
                            <option value="0" @if ($data['frequency'] == 0) selected @endif>Single Time
                            </option>
                            <option value="1" @if ($data['frequency'] == 1) selected @endif>Multiple Time
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Launching Date & Time</label>
                        <input type="datetime-local" name="date" class="form-control" id="date"
                            value="{{ $data['date'] }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiredDate" class="form-label">Expired Date & Time</label>
                        <input type="datetime-local" name="expiredDate" class="form-control" id="expiredDate"
                            value="{{ $data['expired'] }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image Promotion</label>
                        <input type="file" name="image" class="form-control" id="image"
                            value="{{ $data['image'] }}" accept="image/*">
                    </div>
                    <!-- Display the current image or file name if available -->
                    @if ($data['image'])
                        <div class="mt-2">
                            <strong>Current Image:</strong> <a href="{{ $data['image'] }}" target="_blank"
                                style="text-decoration: none;">View
                                Image</a>
                            <br>
                            <img src="{{ $data['image'] }}" alt="Current Image"
                                style="max-width: 200px; max-height: 200px;" />
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary mb-5" style="float: right">Update</button>
                </form>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>

<script type="module">
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/10.14.1/firebase-analytics.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyC__90eega35RZkQvQ-D2w36IBvuut_U2c",
        authDomain: "city-car-park-e29de.firebaseapp.com",
        projectId: "city-car-park-e29de",
        storageBucket: "city-car-park-e29de.appspot.com",
        messagingSenderId: "172277128360",
        appId: "1:172277128360:web:13bb5cd22463d1a3eab69d",
        measurementId: "G-TZ63S1V8QZ"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
</script>
