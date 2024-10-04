<div class="col-xl-6">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>
            Reserve Bay Chart {{ Date::now()->format('Y') }}
        </div>
        <div class="card-body">
            <canvas id="reserveBayChart" width="100%" height="40"></canvas>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily =
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = "#292b2b";

    // Bar Chart Example
    var ctx2 = document.getElementById("reserveBayChart");

    var reserveChart = new Chart(ctx2, {
        type: "bar",
        data: {
            labels: @json($reserveBay['reserveBayLabels']), // Labels for the chart
            datasets: [{
                label: "Reserve Bay Count",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: @json($reserveBay['reserveBayCounts']), // Data for the chart
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 12,
                    },
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        max: Math.max(...
                            @json($reserveBay['reserveBayCounts'])), // Set max based on sorted counts
                        maxTicksLimit: 5
                    },
                    gridLines: {
                        display: true,
                    },
                }],
            },
            legend: {
                display: true,
            },
        },
    });
</script>
