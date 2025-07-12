<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include('config.php');

$user_id = $_SESSION["user_id"];
$success = $error = "";

// Defaults for form fields
$editing = false;
$edit_id = $edit_start = $edit_end = $edit_date = $edit_duration = "";

// Handle edit
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $sql = "SELECT * FROM SleepLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $edit_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($log = mysqli_fetch_assoc($result)) {
        $editing = true;
        $edit_start = $log['start_time'];
        $edit_end = $log['end_time'];
        $edit_date = $log['log_date'];
    }
    mysqli_stmt_close($stmt);
}

// Add new sleep log
if (isset($_POST['add'])) {
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $log_date = $_POST['log_date'];

    $duration = (strtotime($end) - strtotime($start)) / 3600;
    if ($duration <= 0) {
        $error = "End time must be after start time.";
    } else {
        $sql = "INSERT INTO SleepLogs (user_id, start_time, end_time, duration_hours, log_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "issds", $user_id, $start, $end, $duration, $log_date);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Sleep log added.";
        } else {
            $error = "Error adding log.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Update sleep log
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $log_date = $_POST['log_date'];
    $duration = (strtotime($end) - strtotime($start)) / 3600;

    if ($duration <= 0) {
        $error = "End time must be after start time.";
    } else {
        $sql = "UPDATE SleepLogs SET start_time = ?, end_time = ?, duration_hours = ?, log_date = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdsii", $start, $end, $duration, $log_date, $id, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Sleep log updated.";
        } else {
            $error = "Error updating log.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Delete log
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM SleepLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $success = "Sleep log deleted.";
}

// Fetch logs
$logs = [];
$sql = "SELECT * FROM SleepLogs WHERE user_id = ? ORDER BY log_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $logs[] = $row;
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Sleep Logs</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div>
    <h2>Manage Sleep Logs</h2>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <h3><?php echo $editing ? "Edit" : "Add"; ?> Sleep Log</h3>

        <label>Start Time:</label>
        <input type="datetime-local" name="start_time" value="<?php echo $editing ? date('Y-m-d\TH:i', strtotime($edit_start)) : ""; ?>" required>

        <label>End Time:</label>
        <input type="datetime-local" name="end_time" value="<?php echo $editing ? date('Y-m-d\TH:i', strtotime($edit_end)) : ""; ?>" required>

        <label>Log Date:</label>
        <input type="date" name="log_date" value="<?php echo $editing ? $edit_date : ""; ?>" required>

        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <input type="submit" name="update" value="Update Log">
            <a href="manage_sleep_logs.php">Cancel</a>
        <?php else: ?>
            <input type="submit" name="add" value="Add Log">
        <?php endif; ?>
    </form>

    <hr>

    <h3>Your Sleep Logs</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Log Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration (hrs)</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo $log['log_date']; ?></td>
                <td><?php echo date('Y-m-d H:i', strtotime($log['start_time'])); ?></td>
                <td><?php echo date('Y-m-d H:i', strtotime($log['end_time'])); ?></td>
                <td><?php echo number_format($log['duration_hours'], 2); ?></td>
                <td>
                    <a href="?edit=<?php echo $log['id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $log['id']; ?>" onclick="return confirm('Delete this log?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
