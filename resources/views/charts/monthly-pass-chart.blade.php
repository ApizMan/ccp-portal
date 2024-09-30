<div class="col-xl-6">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-pie-chart me-1"></i>
            Monthly Pass Transaction Chart
        </div>
        <div class="card-body">
            <canvas id="myPieChart" width="100%" height="40"></canvas>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily =
        '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Pie Chart Example
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($monthlyPass['labels']),
            datasets: [{
                data: @json($monthlyPass['amounts']),
                backgroundColor: @json($monthlyPass['colors']),
            }],
        },
    });
</script>
