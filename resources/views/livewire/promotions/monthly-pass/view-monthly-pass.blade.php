<div id="layoutSidenav">

    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
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
                        rel="noopener noreferrer">{{ $data['image'] }}</a>
                </div>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
