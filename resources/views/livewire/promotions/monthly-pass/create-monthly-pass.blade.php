<div id="layoutSidenav">

    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Create New Promotion Monthly Pass</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">City Car Park</li>
                </ol>
                <form class="mx-5 my-5" action="{{ route('promotion.promotion.monthly_pass_store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" id="description">
                    </div>
                    <div class="mb-3">
                        <label for="rate" class="form-label">Rate (%)</label>
                        <input type="number" name="rate" class="form-control" id="rate" min="0"
                            step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Launching Date & Time</label>
                        <input type="datetime-local" name="date" class="form-control" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Expired Date & Time</label>
                        <input type="datetime-local" name="expiredDate" class="form-control" id="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image Promotion</label>
                        <input type="file" name="image" class="form-control" id="image" accept="image/*"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-5" style="float: right">Create</button>
                </form>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
