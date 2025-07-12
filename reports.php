<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include('config.php');
$user_id = $_SESSION["user_id"];

// Inputs
$activity_type = $_GET['activity_type'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// Prepare filters
$filter_clause = " AND log_date IS NOT NULL ";
$params = [];

if ($from_date && $to_date) {
    $filter_clause .= " AND log_date BETWEEN ? AND ? ";
    $params[] = $from_date;
    $params[] = $to_date;
}

// Fetch data based on activity type
$data = [];
if ($activity_type == 'water') {
    $sql = "SELECT log_date, quantity, unit FROM WaterLogs WHERE user_id = ? $filter_clause ORDER BY log_date DESC";
} elseif ($activity_type == 'exercise') {
    $sql = "SELECT log_date, exercise_type, duration_minutes FROM ExerciseLogs WHERE user_id = ? $filter_clause ORDER BY log_date DESC";
} elseif ($activity_type == 'sleep') {
    $sql = "SELECT log_date, start_time, end_time, duration_hours FROM SleepLogs WHERE user_id = ? $filter_clause ORDER BY log_date DESC";
} elseif ($activity_type == 'mood') {
    $sql = "SELECT log_date, mood, notes FROM MoodLogs WHERE user_id = ? $filter_clause ORDER BY log_date DESC";
} else {
    $sql = "";
}

if ($sql) {
    $stmt = mysqli_prepare($conn, $sql);
    if ($params) {
        $types = str_repeat("s", count($params));
        mysqli_stmt_bind_param($stmt, "s" . $types, $user_id, ...$params);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
}

// Fetch Mood Logs for last 7 days for trend
$trend = [];
$trend_sql = "SELECT log_date, mood FROM MoodLogs WHERE user_id = ? AND log_date >= CURDATE() - INTERVAL 7 DAY ORDER BY log_date ASC";
$trend_stmt = mysqli_prepare($conn, $trend_sql);
mysqli_stmt_bind_param($trend_stmt, "i", $user_id);
mysqli_stmt_execute($trend_stmt);
$trend_result = mysqli_stmt_get_result($trend_stmt);
while ($row = mysqli_fetch_assoc($trend_result)) {
    $trend[] = $row;
}
mysqli_stmt_close($trend_stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reports & Trends</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div>
    <h2>Reports & Search Filters</h2>

    <form method="get">
        <label>Activity Type:</label>
        <select name="activity_type" required>
            <option value="">-- Select --</option>
            <option value="water" <?php if ($activity_type == 'water') echo 'selected'; ?>>Water Logs</option>
            <option value="exercise" <?php if ($activity_type == 'exercise') echo 'selected'; ?>>Exercise Logs</option>
            <option value="sleep" <?php if ($activity_type == 'sleep') echo 'selected'; ?>>Sleep Logs</option>
            <option value="mood" <?php if ($activity_type == 'mood') echo 'selected'; ?>>Mood Logs</option>
        </select>

        <label>From Date:</label>
        <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">

        <label>To Date:</label>
        <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">

        <input type="submit" value="Search">
    </form>

    <hr>

    <?php if ($activity_type && $data): ?>
        <h3><?php echo ucfirst($activity_type); ?> Logs</h3>
        <table border="1" cellpadding="8">
            <tr>
                <?php foreach (array_keys($data[0]) as $col): ?>
                    <th><?php echo ucfirst(str_replace("_", " ", $col)); ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <?php foreach ($row as $value): ?>
                        <td><?php echo htmlspecialchars($value); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($activity_type): ?>
        <p>No records found.</p>
    <?php endif; ?>

    <hr>

    <h3>Mood Trend (Last 7 Days)</h3>
    <?php if ($trend): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Date</th>
                <th>Mood</th>
            </tr>
            <?php foreach ($trend as $t): ?>
                <tr>
                    <td><?php echo $t['log_date']; ?></td>
                    <td><?php echo htmlspecialchars($t['mood']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No mood data for the last 7 days.</p>
    <?php endif; ?>
</div>
</body>
</html>
