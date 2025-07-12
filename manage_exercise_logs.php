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
$edit_id = $edit_type = $edit_duration = $edit_date = "";
$editing = false;

// If edit request
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $sql = "SELECT * FROM ExerciseLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $edit_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($log = mysqli_fetch_assoc($result)) {
        $editing = true;
        $edit_type = $log['exercise_type'];
        $edit_duration = $log['duration_minutes'];
        $edit_date = $log['log_date'];
    }
    mysqli_stmt_close($stmt);
}

// Add new log
if (isset($_POST['add'])) {
    $exercise_type = $_POST['exercise_type'];
    $duration = $_POST['duration_minutes'];
    $log_date = $_POST['log_date'];

    $sql = "INSERT INTO ExerciseLogs (user_id, exercise_type, duration_minutes, log_date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isis", $user_id, $exercise_type, $duration, $log_date);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Exercise log added.";
    } else {
        $error = "Error adding log.";
    }
    mysqli_stmt_close($stmt);
}

// Update log
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $exercise_type = $_POST['exercise_type'];
    $duration = $_POST['duration_minutes'];
    $log_date = $_POST['log_date'];

    $sql = "UPDATE ExerciseLogs SET exercise_type = ?, duration_minutes = ?, log_date = ? WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sisii", $exercise_type, $duration, $log_date, $id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Log updated.";
    } else {
        $error = "Error updating log.";
    }
    mysqli_stmt_close($stmt);
}

// Delete log
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM ExerciseLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $success = "Log deleted.";
}

// Fetch logs
$logs = [];
$sql = "SELECT * FROM ExerciseLogs WHERE user_id = ? ORDER BY log_date DESC";
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
    <title>Manage Exercise Logs</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div>
    <h2>Manage Exercise Logs</h2>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <h3><?php echo $editing ? "Edit" : "Add"; ?> Exercise Log</h3>

        <label>Exercise Type:</label>
        <input type="text" name="exercise_type" value="<?php echo htmlspecialchars($edit_type); ?>" required>

        <label>Duration (minutes):</label>
        <input type="number" name="duration_minutes" value="<?php echo htmlspecialchars($edit_duration); ?>" required>

        <label>Date:</label>
        <input type="date" name="log_date" value="<?php echo htmlspecialchars($edit_date); ?>" required>

        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <input type="submit" name="update" value="Update Log">
            <a href="manage_exercise_logs.php">Cancel</a>
        <?php else: ?>
            <input type="submit" name="add" value="Add Log">
        <?php endif; ?>
    </form>

    <hr>

    <h3>Your Exercise Logs</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Date</th>
            <th>Exercise</th>
            <th>Duration</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo $log['log_date']; ?></td>
                <td><?php echo htmlspecialchars($log['exercise_type']); ?></td>
                <td><?php echo $log['duration_minutes']; ?> min</td>
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
