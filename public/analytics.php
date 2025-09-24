<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #chart-container {
            max-width: 700px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">Analytics Dashboard</h1>
    <div id="chart-container">
        <canvas id="tasksChart"></canvas>
    </div>

    <script>
    async function loadChart() {
        const res = await fetch('api/tasks_summary.php');
        const data = await res.json();

        const labels = data.map(row => 'Week ' + row.week);
        const counts = data.map(row => row.completed_count);

        const ctx = document.getElementById('tasksChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tasks Completed',
                    data: counts
                }]
            }
        });
    }
    loadChart();
    </script>

</body>
</html>
