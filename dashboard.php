<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include('config.php');

$user_id = $_SESSION["user_id"];
$current_date = date('Y-m-d');
$week_ago = date('Y-m-d', strtotime('-6 days'));

// Fetch today's logs
$water_query = "SELECT * FROM waterlogs WHERE user_id = ? AND log_date = ?";
$water_stmt = $conn->prepare($water_query);
$water_stmt->bind_param("is", $user_id, $current_date);
$water_stmt->execute();
$water_logs = $water_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$sleep_query = "SELECT * FROM sleeplogs WHERE user_id = ? AND log_date = ?";
$sleep_stmt = $conn->prepare($sleep_query);
$sleep_stmt->bind_param("is", $user_id, $current_date);
$sleep_stmt->execute();
$sleep_logs = $sleep_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$exercise_query = "SELECT * FROM exerciselogs WHERE user_id = ? AND log_date = ?";
$exercise_stmt = $conn->prepare($exercise_query);
$exercise_stmt->bind_param("is", $user_id, $current_date);
$exercise_stmt->execute();
$exercise_logs = $exercise_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$mood_query = "SELECT * FROM moodlogs WHERE user_id = ? AND log_date = ?";
$mood_stmt = $conn->prepare($mood_query);
$mood_stmt->bind_param("is", $user_id, $current_date);
$mood_stmt->execute();
$mood_logs = $mood_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch weekly trends
$weekly_water = $conn->prepare("SELECT log_date, SUM(quantity) as total FROM waterlogs WHERE user_id = ? AND log_date BETWEEN ? AND ? GROUP BY log_date ORDER BY log_date");
$weekly_water->bind_param("iss", $user_id, $week_ago, $current_date);
$weekly_water->execute();
$weekly_water_data = $weekly_water->get_result()->fetch_all(MYSQLI_ASSOC);

$weekly_sleep = $conn->prepare("SELECT log_date, SUM(duration_hours) as total FROM sleeplogs WHERE user_id = ? AND log_date BETWEEN ? AND ? GROUP BY log_date ORDER BY log_date");
$weekly_sleep->bind_param("iss", $user_id, $week_ago, $current_date);
$weekly_sleep->execute();
$weekly_sleep_data = $weekly_sleep->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch reminders
$reminders_query = "SELECT * FROM reminders WHERE user_id = ? AND is_active = 1 AND time >= CURTIME() AND time <= ADDTIME(CURTIME(), '24:00:00')";
$reminders_stmt = $conn->prepare($reminders_query);
$reminders_stmt->bind_param("i", $user_id);
$reminders_stmt->execute();
$reminders = $reminders_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Check for missing activities
$missing_activities = [];
if (empty($water_logs)) $missing_activities[] = "Water intake";
if (empty($sleep_logs)) $missing_activities[] = "Sleep";
if (empty($exercise_logs)) $missing_activities[] = "Exercise";
if (empty($mood_logs)) $missing_activities[] = "Mood";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: auto;
        }
        .dashboard-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .card h3 {
            margin-top: 0;
            color: #333;
        }
        .no-results {
            text-align: center;
            color: #e63946;
            font-size: 18px;
        }
        .notification {
            background: #fffae6;
            border: 1px solid #ffd700;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert {
            background: #ffe6e6;
            border: 1px solid #ff4d4d;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .chart-container {
            max-width: 100%;
            margin: 20px 0;
        }
        @media (min-width: 768px) {
            .dashboard-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <section>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h2>

        <?php if (!empty($missing_activities)): ?>
            <div class="alert">
                <strong>Alert:</strong> You haven't logged the following activities for today: 
                <?php echo implode(", ", $missing_activities); ?>. Please log them to stay on track!
            </div>
        <?php endif; ?>

        <?php if (!empty($reminders)): ?>
            <div class="notification">
                <strong>Upcoming Reminders:</strong>
                <ul>
                    <?php foreach ($reminders as $reminder): ?>
                        <li><?php echo htmlspecialchars($reminder['reminder_type']); ?> at <?php echo date('h:i A', strtotime($reminder['time'])); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="dashboard-container">
            <!-- Today's Logs -->
            <div class="card">
                <h3>Today's Water Intake</h3>
                <?php if (empty($water_logs)): ?>
                    <p class="no-results">No water intake logged today.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($water_logs as $log): ?>
                            <li><?php echo htmlspecialchars($log['quantity']) . " " . htmlspecialchars($log['unit']); ?> on <?php echo $log['log_date']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3>Today's Sleep</h3>
                <?php if (empty($sleep_logs)): ?>
                    <p class="no-results">No sleep logged today.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($sleep_logs as $log): ?>
                            <li><?php echo htmlspecialchars($log['duration_hours']); ?> hours on <?php echo $log['log_date']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3>Today's Exercise</h3>
                <?php if (empty($exercise_logs)): ?>
                    <p class="no-results">No exercise logged today.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($exercise_logs as $log): ?>
                            <li><?php echo htmlspecialchars($log['exercise_type']); ?> for <?php echo htmlspecialchars($log['duration_minutes']); ?> minutes on <?php echo $log['log_date']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3>Today's Mood</h3>
                <?php if (empty($mood_logs)): ?>
                    <p class="no-results">No mood logged today.</p>
                <?php else: ?>
                    <ul>
                        <?php foreach ($mood_logs as $log): ?>
                            <li><?php echo htmlspecialchars($log['mood']); ?> - <?php echo htmlspecialchars($log['notes'] ?? 'No notes'); ?> on <?php echo $log['log_date']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Weekly Trends -->
            <div class="card">
                <h3>Weekly Water Intake</h3>
                <div class="chart-container">
                    <canvas id="waterChart"></canvas>
                </div>
            </div>

            <div class="card">
                <h3>Weekly Sleep Hours</h3>
                <div class="chart-container">
                    <canvas id="sleepChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Prepare data for charts
        const waterLabels = [];
        const waterData = [];
        <?php foreach ($weekly_water_data as $data): ?>
            waterLabels.push('<?php echo $data['log_date']; ?>');
            waterData.push(<?php echo $data['total']; ?>);
        <?php endforeach; ?>

        const sleepLabels = [];
        const sleepData = [];
        <?php foreach ($weekly_sleep_data as $data): ?>
            sleepLabels.push('<?php echo $data['log_date']; ?>');
            sleepData.push(<?php echo $data['total']; ?>);
        <?php endforeach; ?>

        // Water Chart
        const waterCtx = document.getElementById('waterChart').getContext('2d');
        new Chart(waterCtx, {
            type: 'bar',
            data: {
                labels: waterLabels,
                datasets: [{
                    label: 'Water Intake (glasses)',
                    data: waterData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Sleep Chart
        const sleepCtx = document.getElementById('sleepChart').getContext('2d');
        new Chart(sleepCtx, {
            type: 'bar',
            data: {
                labels: sleepLabels,
                datasets: [{
                    label: 'Sleep Hours',
                    data: sleepData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>