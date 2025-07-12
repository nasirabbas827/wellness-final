<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

include('config.php');

$user_id = $_SESSION["user_id"];
$success = $error = "";

// Form defaults
$editing = false;
$edit_id = $edit_type = $edit_time = "";
$edit_active = 1;

// Handle edit
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $sql = "SELECT * FROM Reminders WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $edit_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $editing = true;
        $edit_type = $row['reminder_type'];
        $edit_time = $row['time'];
        $edit_active = $row['is_active'];
    }
    mysqli_stmt_close($stmt);
}

// Add reminder
if (isset($_POST['add'])) {
    $type = trim($_POST['reminder_type']);
    $time = $_POST['time'];
    $active = isset($_POST['is_active']) ? 1 : 0;

    $sql = "INSERT INTO Reminders (user_id, reminder_type, time, is_active) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issi", $user_id, $type, $time, $active);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Reminder added.";
    } else {
        $error = "Failed to add reminder.";
    }
    mysqli_stmt_close($stmt);
}

// Update reminder
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $type = trim($_POST['reminder_type']);
    $time = $_POST['time'];
    $active = isset($_POST['is_active']) ? 1 : 0;

    $sql = "UPDATE Reminders SET reminder_type = ?, time = ?, is_active = ? WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiii", $type, $time, $active, $id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Reminder updated.";
    } else {
        $error = "Failed to update reminder.";
    }
    mysqli_stmt_close($stmt);
}

// Delete reminder
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM Reminders WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $success = "Reminder deleted.";
}

// Fetch reminders
$reminders = [];
$sql = "SELECT * FROM Reminders WHERE user_id = ? ORDER BY time";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $reminders[] = $row;
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Reminders</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<div>
    <h2>Manage Reminders</h2>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        <h3><?php echo $editing ? "Edit" : "Add"; ?> Reminder</h3>

        <label>Reminder Type:</label>
        <input type="text" name="reminder_type" value="<?php echo htmlspecialchars($edit_type); ?>" required>

        <label>Reminder Time:</label>
        <input type="time" name="time" value="<?php echo htmlspecialchars($edit_time); ?>" required>

        <label>
            <input type="checkbox" name="is_active" <?php echo ($edit_active || !$editing) ? "checked" : ""; ?>>
            Active
        </label>

        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
            <input type="submit" name="update" value="Update Reminder">
            <a href="manage_reminders.php">Cancel</a>
        <?php else: ?>
            <input type="submit" name="add" value="Add Reminder">
        <?php endif; ?>
    </form>

    <hr>

    <h3>Your Reminders</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Type</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($reminders as $reminder): ?>
            <tr>
                <td><?php echo htmlspecialchars($reminder['reminder_type']); ?></td>
                <td><?php echo $reminder['time']; ?></td>
                <td><?php echo $reminder['is_active'] ? "Active" : "Inactive"; ?></td>
                <td>
                    <a href="?edit=<?php echo $reminder['id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $reminder['id']; ?>" onclick="return confirm('Delete this reminder?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
