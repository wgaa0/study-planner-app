<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$pageTitle = "Analytics Dashboard";
ob_start();
?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Analytics Dashboard</h1>
    <div id="chart-container" class="bg-white rounded-2xl shadow p-6 max-w-3xl mx-auto">
        <canvas id="tasksChart"></canvas>
    </div>
</div>

<script src="./assets/vendor/chartjs/chart.umd.js"></script>
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
                data: counts,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}
loadChart();
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '../partials/layout.php';
?>
