<div id="layoutSidenav">

    <!-- Include the Sidebar -->
    @include('layouts.side-nav')

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">City Car Park</li>
                </ol>
                <div class="row">

                    @include('charts.parking-chart', ['parking' => $parking])

                    @include('charts.compound-chart')

                    @include('charts.monthly-pass-chart', ['monthlyPass' => $monthlyPassData])

                    @include('charts.reserve-bay-chart', ['reserveBay' => $reserveBayData])
                </div>
            </div>
        </main>

        @include('layouts.footer')

    </div>
</div>
