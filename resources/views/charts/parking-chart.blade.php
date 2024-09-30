<div class="col-xl-6">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-area me-1"></i>
            Parking Transaction Chart ({{ date('Y') }})
        </div>
        <div class="card-body">
            <canvas id="myAreaChart" width="100%" height="40"></canvas>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily =
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2b';

    // Sort labels and counts based on chronological order
    let labels = @json($parking['labels']);
    let counts = @json($parking['counts']);

    // Create an array of objects to sort both labels and counts simultaneously
    let sortedData = labels.map((label, index) => ({
        label,
        count: counts[index]
    }));

    // Define a sorting function for month-year format labels
    sortedData.sort((a, b) => new Date(a.label) - new Date(b.label));

    // Separate the sorted data back into labels and counts arrays
    labels = sortedData.map(item => item.label);
    counts = sortedData.map(item => item.count);

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels, // Use sorted labels
            datasets: [{
                label: "Parking Transactions",
                lineTension: 0.3,
                backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: counts, // Use sorted counts
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 12
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: Math.max(...counts), // Set max based on sorted counts
                        maxTicksLimit: 5
                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, .125)",
                    }
                }],
            },
            legend: {
                display: false
            }
        }
    });
</script>
