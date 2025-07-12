<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include('config.php');

$user_id = $_SESSION["user_id"];
$success = $error = "";

// Default form variables
$editing = false;
$edit_id = $edit_mood = $edit_notes = $edit_date = "";

// Handle edit mode
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $sql = "SELECT * FROM MoodLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $edit_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($log = mysqli_fetch_assoc($result)) {
        $editing = true;
        $edit_mood = $log['mood'];
        $edit_notes = $log['notes'];
        $edit_date = $log['log_date'];
    }
    mysqli_stmt_close($stmt);
}

// Add mood log
if (isset($_POST['add'])) {
    $mood = trim($_POST['mood']);
    $notes = trim($_POST['notes']);
    $log_date = $_POST['log_date'];

    $sql = "INSERT INTO MoodLogs (user_id, mood, notes, log_date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $user_id, $mood, $notes, $log_date);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Mood log added.";
    } else {
        $error = "Error adding log.";
    }
    mysqli_stmt_close($stmt);
}

// Update mood log
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $mood = trim($_POST['mood']);
    $notes = trim($_POST['notes']);
    $log_date = $_POST['log_date'];

    $sql = "UPDATE MoodLogs SET mood = ?, notes = ?, log_date = ? WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssii", $mood, $notes, $log_date, $id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Mood log updated.";
    } else {
        $error = "Error updating log.";
    }
    mysqli_stmt_close($stmt);
}

// Delete mood log
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM MoodLogs WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $success = "Mood log deleted.";
}

// Fetch logs
$logs = [];
$sql = "SELECT * FROM MoodLogs WHERE user_id = ? ORDER BY log_date DESC";
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
    <title>Manage Mood Logs</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div>
    <h2>Manage Mood Logs</h2>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <h3><?php echo $editing ? "Edit" : "Add"; ?> Mood Log</h3>

        <label>Mood:</label>
        <input type="text" name="mood" value="<?php echo htmlspecialchars($edit_mood); ?>" required>

        <label>Notes (optional):</label>
        <textarea name="notes"><?php echo htmlspecialchars($edit_notes); ?></textarea>

        <label>Date:</label>
        <input type="date" name="log_date" value="<?php echo htmlspecialchars($edit_date); ?>" required>

        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <input type="submit" name="update" value="Update Log">
            <a href="manage_mood_logs.php">Cancel</a>
        <?php else: ?>
            <input type="submit" name="add" value="Add Log">
        <?php endif; ?>
    </form>

    <hr>

    <h3>Your Mood Logs</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Date</th>
            <th>Mood</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo $log['log_date']; ?></td>
                <td><?php echo htmlspecialchars($log['mood']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($log['notes'])); ?></td>
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
